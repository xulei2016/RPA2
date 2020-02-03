<?php

namespace App\Http\Controllers\api\Mediator;

use App\Http\Controllers\api\BaseApiController;
use App\Models\Admin\Base\Organization\SysDept;
use App\Models\Index\Mediator\FuncMediatorChangeList;
use App\Models\Index\Mediator\FuncMediatorFlow;
use App\Models\Index\Mediator\FuncMediatorInfo;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\Admin\Api\RpaHaLcztcx;
use Mpdf\Mpdf;

class MediatorApiController extends BaseApiController
{
    /**
     * 新签
     * 后台审核完成，写入crm居间待审核表（TXCTC_JJRXXCL），
     * crm自动触发居间比例流程
     * 监控比例流程完成，修改数据库状态，生成协议，影像归档
     *
     * 续签
     * 后台审核完成，写入crm居间人续签处理表（TXCTC_JJR_XQDJ）。
     * crm自动触发续签申请
     * 监控续签审核，修改数据库状态，生成协议，影像归档
     *
     * 信息变更
     * 监控crm信息变更流程，根据变更的内容写入变更表，在流程表加一条记录
     * 将该居间未完成的流程作废
     *
     * 注销
     * 监控crm注销流程，在流程表写入居间人注销流程，修改主表居间人状态
     * 将该居间未完成的流程作废
     *
     * 续签确认
     * 监控crm续签确认流程，在客户经理通过后修改流程表状态
     */


    /**
     * 居间人流程同步
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mediator_flow(Request $request)
    {
        //ip检测
//        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
//        if($res !== true){
//            return response()->json($res);
//        }

        //表单验证
        $validatedData = $request->validate([
            'id' => 'required|numeric',
        ]);
        $lc = RpaHaLcztcx::find($request->id);
        if($lc){
            $return = $this->jjrDistribute($lc);
        }else{
            $return = [
                'status' => 500,
                'msg' => "未找到流程"
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($return,true),$request->getClientIp());

        return response()->json($return);
    }

    /**************************************居间信息处理业务**********************************/

    /**
     * 居间人业务分发
     */
    private function jjrDistribute($lc)
    {
        if($lc->state >= 4){
            switch($lc->tablename){
                case "TXCTC_LC_JJR_XZ":  //新增
                    $re = $this->jjrAdd($lc->instid);
                    break;
                case "TXCTC_LC_JJR_XG": //变更
                    $re =  $this->jjrChange($lc->instid);
                    break;
                case "TXCTC_LC_JJR_XQSQ": //续签
                    $re =  $this->jjrXQ($lc->instid);
                    break;
                case "TXCTC_LC_JJR_ZXSQ": //注销
                    $re = $this->jjrZXSQ($lc->instid);
                    break;
                case "TXCTC_LC_JJRXQQR": //续签确认
                    $re = $this->jjrXQQR($lc->instid);
                    break;
                case "TXCTC_LC_JJRBLQR": //比例确认
                    $re = $this->jjrBLQR($lc->instid);
                    break;
                default:
                    $re = [
                        'status' => 500,
                        'msg' => "该流程暂无业务处理！"
                    ];
                    break;
            }
        }else{
            $re = [
                'status' => 500,
                'msg' => "该流程还未完成！"
            ];
        }
        return $re;
    }

    /**
     * 居间新增流程
     * @param $instid
     * @return array
     * @throws \Mpdf\MpdfException
     */
    private function jjrAdd($instid)
    {
        $table = 'TXCTC_LC_JJR_XZ';

        $data = $this->getLcData($instid,$table);
        $info = FuncMediatorInfo::where('zjbh',$data['SFZH'])->first();
        if(!$info){
            //线下居间，同步到rpa
            $this->addMediator($data);
        }

        $re = [
            'status' => 200,
            'msg' => "新增流程{$instid},数据同步成功"
        ];

        return $re;
    }

    /**
     * 比例确认流程
     * @param $instid
     * @return array
     * @throws \Mpdf\MpdfException
     */
    private function jjrBLQR($instid)
    {
        $table = 'TXCTC_LC_JJRBLQR';

        $data = $this->getLcData($instid,$table);
        $info = FuncMediatorInfo::where('zjbh',$data['SFZH'])->first();
        if($info){
//            1.修改流程表状态
            $flow = FuncMediatorFlow::where([['uid',$info->id],['status',1],['is_handle',0]])->whereIn('type',[0,1])->first();
            if($flow){
                $flow->is_handle = 1;
                $flow->handle_time = date('Y-m-d H:i:s');
                $flow->xy_date_begin = $this->crmDateFormat($data['XYKSRQ']);
                $flow->xy_date_end = $this->crmDateFormat($data['XYJSRQ']);
                $flow->save();
//            2.移动文件
                $path = $this->moveFile($flow);
//            3.生成协议
                $this->getAgreementFile($flow,$path);
//            4.同步数据到主表
                $this->syncToInfo($flow);
            }else{
                $re = [
                    'status' => 500,
                    'msg' => "未查询到数据！"
                ];
                return $re;
            }
        }else{
            $re = [
                'status' => 500,
                'msg' => "未查询到数据！"
            ];
            return $re;
        }

        $re = [
            'status' => 200,
            'msg' => "比例确认流程{$instid},数据同步成功"
        ];

        return $re;
    }

    /**
     * 居间续签流程
     * @param $instid
     * @return array
     * @throws \Mpdf\MpdfException
     */
    private function jjrXQ($instid)
    {
        $table = 'TXCTC_LC_JJR_XQSQ';

        $data = $this->getLcData($instid,$table);
        $info = FuncMediatorInfo::where('zjbh',$data['SFZH'])->first();
        if(!$info){
            //线下居间，同步到rpa
            $this->addMediator($data);
        }else{
//            线上居间
//            1.修改流程表状态
            $flow = FuncMediatorFlow::where([['uid',$info->id],['status',1],['is_handle',0]])->whereIn('type',[0,1])->first();
            if($flow){
                $flow->is_handle = 1;
                $flow->handle_time = date('Y-m-d H:i:s');
                $flow->xy_date_begin = $this->crmDateFormat($data['XYKSRQ']);
                $flow->xy_date_end = $this->crmDateFormat($data['XYJSRQ']);
                $flow->save();
//            2.移动文件
                $path = $this->moveFile($flow);
//            3.生成协议
                $this->getAgreementFile($flow,$path);
//            4.同步数据到主表
                $this->syncToInfo($flow);
            }else{
                $re = [
                    'status' => 500,
                    'msg' => "未查询到数据！"
                ];
                return $re;
            }
        }

        $re = [
            'status' => 200,
            'msg' => "续签流程{$instid},数据同步成功"
        ];

        return $re;
    }

    /**
     * 居间变更流程
     * @param $instid
     * @return array
     */
    private function jjrChange($instid)
    {

        $table = 'TXCTC_LC_JJR_XG';

        $data = $this->getLcData($instid,$table);

        $this->addFlow($data,'XG');

        $re = [
            'status' => 200,
            'msg' => "修改流程{$instid},数据同步成功"
        ];
        return $re;
    }

    //居间注销流程
    private function jjrZXSQ($instid)
    {
        $table = 'TXCTC_LC_JJR_ZXSQ';

        $data = $this->getLcData($instid,$table);

        $this->addFlow($data,'ZX');

        $re = [
            'status' => 200,
            'msg' => "注销流程{$instid},数据同步成功"
        ];
        return $re;
    }

    /**
     * 居间人续签确认流程
     * @param $instid
     * @return array
     */
    private function jjrXQQR($instid)
    {
        $table = 'TXCTC_LC_JJRXQQR';

        $data = $this->getLcData($instid,$table);
        if($data['QRLX'] == 1){
            $flow = FuncMediatorFlow::where([['zjbh',$data['SFZH']],['status',1],['is_handle',1]])->whereIn('type',[0,1])->orderBy('id','desc')->first();
            $flow->is_manager_agree = 1;
            $flow->agree_time = date('Y-m-d H:i:s');
            $flow->save();
        }

        $re = [
            'status' => 200,
            'msg' => "续签确认流程{$instid},数据同步成功"
        ];
        return $re;
    }

    /**
     * 获取流程信息
     * @param $instid
     * @param $tabname
     * @return mixed
     */
    private function getLcData($instid,$tabname)
    {
        //获取流程信息
        $sql = "select * from {$tabname} where instid = ".$instid;
        $post_data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'JJR',
                'by' => $sql
            ]
        ];
        $result = $this->getCrmData($post_data);
        return $result[0];
    }

    /**
     * 移动文件
     * @param $flow
     * @return string
     */
    public function moveFile($flow)
    {
        //根目录
        $root = storage_path().config('mediator.file_root');
        //拼接路径
        $dept = $flow->dept->name;
        $name = $flow->info->name."_".$flow->number;
        $date = date('Ym');

        $path = "/居间人影像/线上/".$dept."/".$name."/".$date;
        if(!is_dir($root.$path)){
            mkdir($root.$path,0777,true);
        }

        //原文件地址
        $old_sign_img= $root.$flow->sign_img;
        $old_sfz_zm_img = $root.$flow->sfz_zm_img;
        $old_sfz_fm_img = $root.$flow->sfz_fm_img;
        $old_sfz_sc_img = $root.$flow->sfz_sc_img;
        $old_bank_img = $root.$flow->bank_img;
        $old_exam_img = $root.$flow->exam_img;

        //新文件地址
        $new_sign_img= $path."/居间签字照片.png";
        $new_sfz_zm_img= $path."/身份证正面照.png";
        $new_sfz_fm_img= $path."/身份证反面照.png";
        $new_sfz_sc_img= $path."/手持身份证.png";
        $new_bank_img= $path."/银行卡照片.png";
        $new_exam_img= $path."/从业资格证书.png";

        //移动文件
        rename($old_sign_img,$root.$new_sign_img);
        rename($old_sfz_zm_img,$root.$new_sfz_zm_img);
        rename($old_sfz_fm_img,$root.$new_sfz_fm_img);
        rename($old_sfz_sc_img,$root.$new_sfz_sc_img);
        rename($old_bank_img,$root.$new_bank_img);
        if($flow->is_exam == 1){
            rename($old_exam_img,$root.$new_exam_img);
        }

        //修改数据库
        $flow->sign_img = $new_sign_img;
        $flow->sfz_zm_img = $new_sfz_zm_img;
        $flow->sfz_fm_img = $new_sfz_fm_img;
        $flow->sfz_sc_img = $new_sfz_sc_img;
        $flow->bank_img = $new_bank_img;
        $flow->exam_img = $new_exam_img;
        $flow->agreement_url = $path."/".$flow->info->name."--居间水印协议.pdf";
        $flow->save();
        return $root.$path;
    }

    /**
     * 生成居间协议
     * @param $flow
     * @throws \Mpdf\MpdfException
     */
    private function getAgreementFile($flow,$path)
    {
        $flow->base64_sign_img = $this->base64EncodeImage(storage_path().config('mediator.file_root').$flow->sign_img);
        $mpdf = new Mpdf();
        //设置
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;

        //居间协议
        $mpdf->AddPage();
        $view1 = view('Admin.Mediator.agreement.agreement', ['flow' => $flow]);
        $mpdf->writeHtml($view1);
        $mpdf->SetWatermarkImage('images/Mediator/gz.png','0.7',['60','60'],['25','50']);
        $mpdf->showWatermarkImage = true;

        //居间测试题
        $mpdf->AddPage();
        $mpdf->showWatermarkImage = false;
        $view2 = view('Admin.Mediator.agreement.agreement_2', ['flow' => $flow]);
        $mpdf->writeHtml($view2);

        //居间人自律承诺书
        $mpdf->AddPage();
        $view3 = view('Admin.Mediator.agreement.agreement_3', ['flow' => $flow]);
        $mpdf->writeHtml($view3);

        //居间报酬
        $mpdf->AddPage();
        $view4 = view('Admin.Mediator.agreement.agreement_4', ['flow' => $flow]);
        $mpdf->writeHtml($view4);
        $mpdf->SetWatermarkImage('images/Mediator/gz.png','0.7',['60','60'],['30','150']);
        $mpdf->showWatermarkImage = true;

        $name = $flow->info->name."--居间水印协议.pdf";
        $mpdf->Output($path."/".$name);
        exit;
    }

    /**
     * 同步流程数据到主表
     * @param $flow
     * @return mixed
     */
    private function syncToInfo($flow)
    {
        $update = [
            'sex' => $flow->sex,
            'birthday' => $flow->birthday,
            'dept_id' => $flow->dept_id,
            'manager_number' => $flow->manager_number,
            'number' => $flow->number,
            'xy_date_end' => $flow->xy_date_end,
            'email' => $flow->email,
            'is_exam' => $flow->is_exam,
            'exam_img' => $flow->exam_img,
            'edu_background' => $flow->edu_background,
            'edu_img' => $flow->edu_img,
            'edu_degree_img' => $flow->edu_degree_img,
            'sign_img' => $flow->sign_img,
            'address' => $flow->address,
            'postal_code' => $flow->postal_code,
            'profession' => $flow->profession,
            'rate' => $flow->rate,
            'jr_rate' => $flow->jr_rate,
            'sfz_zm_img' => $flow->sfz_zm_img,
            'sfz_fm_img' => $flow->sfz_fm_img,
            'sfz_sc_img' => $flow->sfz_sc_img,
            'sfz_date_end' => $flow->sfz_date_end,
            'sfz_address' => $flow->sfz_address,
            'bank_number' => $flow->bank_number,
            'bank_name' => $flow->bank_name,
            'bank_branch' => $flow->bank_branch,
            'bank_username' => $flow->bank_username,
            'bank_img' => $flow->bank_img,
            'status' => 1
        ];
        //新签，需要更新开户日期
        if($flow->type == 0){
            $update['open_time'] = date('Y-m-d H:i:s');
        }

        return FuncMediatorInfo::where('id',$flow->uid)->update($update);
    }

    /**
     * 线下居间新增
     * @param $data
     */
    private function addMediator($data,$type)
    {
//        获取居间人完整信息
        $post_data = [
            'type' => 'jjr',
            'action' => 'getJjrBy',
            'param' => [
                'by' => [
                    ['SFZH','=',$data['SFZH']]
                ]
            ]
        ];
        $result = $this->getCrmData($post_data);
        $jjr = $result[0];

//        增加主表
        $add1 = [
           'name' =>  $jjr['XM'],
           'phone' =>  $jjr['LXSJ'] ? $jjr['LXSJ'] : $jjr['DH'],
           'zjbh' =>  $jjr['SFZH'],
           'open_time' =>  $jjr['KHRQ   '],
        ];
        $info = FuncMediatorInfo::create($add1);
//        增加流程表
        $add2 = [
            'uid' => $info->id,
            'type' => $type,
            'dept_id' => $this->getDeptId($jjr['YYB']),
            'zjbh' => $jjr['SFZH'],
            'manager_number' => $this->getManagerNumber($jjr['KFR']),
            'number' => $jjr['BH'],
            'sex' => $this->getDm('SEX',$jjr['XB']),
            'birthday' => '',
            'email' => $jjr['EMAIL'],
            'edu_background' => $this->getDm('XL',$jjr['XL']),
            'address' => $jjr['LXDZ'],
            'postal_code' => $jjr['YZBM'],
            'profession' => $jjr['ZY'],
            'is_exam' => 0,
            'exam_number' => '',
            'rate' => $jjr['TCFAMC'],
            'jr_rate' => '',
            'xy_date_begin' => $this->crmDateFormat($jjr['XYKSRQ']),
            'xy_date_end' => $this->crmDateFormat($jjr['XYJSRQ']),
            'sfz_date_end' => $this->crmDateFormat($jjr['ZJDQ']),
            'sfz_address' => $jjr['JZDZ'],
            'bank_number' => $jjr['YHZH'],
            'bank_name' => $jjr['KHYH'],
            'bank_branch' => $jjr['FHMC'],
            'bank_username' => $jjr['XM'],
            'from' => 3,
            'remark' => $jjr['BZ'],
            'is_check' => 1,
            'is_sure' => 1,
            'is_handle' => 1,
            'status' => 1,
            'crmflow_end_time' => date('Y-m-d H:i:s'),
        ];
        FuncMediatorFlow::create($add2);
    }

    /**
     * 添加流程
     * @param $data
     * @param $type
     */
    private function addFlow($data,$type)
    {
        $info = FuncMediatorInfo::where('zjbh',$data['SFZH'])->first();

        if($type == 'ZX'){
            //注销
            //1.新增流程
            $add = [
                'type' => 3,
                'uid' => $info->id,
                'zjbh' => $info->zjbh,
                'from' => 3,
                'is_check' => 1,
                'is_sure' => 1,
                'is_handle' => 1,
                'xy_date_end' => $this->crmDateFormat($data['XYJSRQ']),
                'crmflow_end_time' => date('Y-m-d H:i:s')
            ];
            FuncMediatorFlow::create($add);

            //2.将未完成的流程作废
            FuncMediatorFlow::where([['uid',$info->id],['status',1],['is_handle',0]])->whereIn('type',[0,1])->update(['status'=>0]);

            //3.修改主表状态
            $info->status = 3;
            $info->save();
        }elseif ($type == 'XG'){
            //更新
            //1.新增流程
            $add = [
                'type' => 2,
                'uid' => $info->id,
                'zjbh' => $info->zjbh,
                'from' => 3,
                'is_check' => 1,
                'is_sure' => 1,
                'is_handle' => 1,
                'crmflow_end_time' => date('Y-m-d H:i:s')
            ];
            $flow = FuncMediatorFlow::create($add);
            //2.变更信息
            $update = [];
            //手机号
            $phone = isset($data['LXSJ']) ? $data['LXSJ'] : $data['DH'];
            if($phone && $phone != $info->phone){
                $update['phone'] = $phone;
            }
            //银行卡
            if($data['YHZH'] && $data['YHZH'] != $info->bank_number){
                $update['bank_number'] = $data['YHZH'];
                $update['bank_name'] = $data['KHYH'];
                $update['bank_branch'] = $data['FHMC'];
            }
            FuncMediatorInfo::where('id',$info->id)->update($update);
            //3.写入变更表
            foreach ($update as $k => $v){
                $add = [
                    'fid' => $flow->id,
                    'name' => $k,
                    'old' => $info->$k,
                    'new' => $v
                ];
                FuncMediatorChangeList::create($add);
            }
        }
    }

    /**
     * 格式化crm日期
     * @param $date
     * @return string
     */
    private function crmDateFormat($date)
    {
        $Y = substr($date,0,4);
        $m = substr($date,4,2);
        $d = substr($date,6,2);
        return $Y."-".$m."-".$d;
    }

    /**
     * 获取crm数据字典
     * @param $type
     * @param $val
     * @return mixed
     */
    private function getDm($type,$val)
    {
        $sql = "select NOTE from txtdm where FLDM =".$type ." and CBM =".$val;
        $post_data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'JJR',
                'by' => $sql
            ]
        ];
        $result = $this->getCrmData($post_data);
        return $result[0]['NOTE'];
    }

    /**
     * 根据crm获取部门id
     * @param $yyb
     * @return mixed
     */
    private function getDeptId($yyb)
    {
        $dept = SysDept::where('yyb_hs',$yyb)->orWhere('khfz_hs',$yyb)->first();
        return $dept->id;
    }

    /**
     * 获取客户经理号
     * @param $kfr
     * @return mixed
     */
    private function getManagerNumber($kfr)
    {
        $sql = "select * from TXCTC_YGXX where id=".$kfr." order by id desc";
        $post_data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'JJR',
                'by' => $sql
            ]
        ];
        $result = $this->getCrmData($post_data);
        return $result[0]['BH'];
    }
}
