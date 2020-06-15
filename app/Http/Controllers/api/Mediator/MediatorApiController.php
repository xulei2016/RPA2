<?php

namespace App\Http\Controllers\api\Mediator;

use App\Http\Controllers\api\BaseApiController;
use App\Http\Controllers\api\Mediator\webServiceController;
use App\Models\Admin\Base\Organization\SysDept;
use App\Models\Index\Mediator\FuncMediatorChangeList;
use App\Models\Index\Mediator\FuncMediatorFlow;
use App\Models\Index\Mediator\FuncMediatorInfo;
use App\Models\Admin\Func\rpa_jjrvis;
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
     * 监控续签流程完成，获取居间人比例，如有更改需要发送短信，居间人需要登录系统确认比例
     * 修改数据库状态，生成协议，影像归档
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

    /**
     * 获取可审批流程
     */
    public function getTaskList()
    {
        $webservice = new webServiceController();
        $taskList = $webservice->queryTaskList();
        $webservice->logout();
        $errArr = [];
        if($taskList){
            $list = null;
            if(isset($taskList->values)){
                $list[0] = $taskList;
            }else{
                $list = $taskList;
            }
            foreach($list as $v){
                //1.获取居间比例
                $instid = $v->values[1];
                $param = [
                    'type' => 'jjr',
                    'action' => 'getRateByInstanceId',
                    'param' => [
                        'instid' => $instid,
                    ]
                ];
                $res = $this->getCrmData($param);
                // echo $instid;exit;
                // print_r($res);exit;
                if(200 == $res['code']){
                    $number = $res['data'];
                }else{
                    $errArr[] = [
                        'instid' => $instid,
                        'errMsg' => '居间比例未找到'
                    ];
                    continue;
                }
                
                //2.是否等于居间填写比例
                $table = 'TXCTC_LC_JJR_XQSQ';
                $columns = "BH,SFZH,XXYKSRQ,XXYJSRQ";
                $data = $this->getLcData($instid,$table,$columns);
                $info = FuncMediatorInfo::where('number',$data['BH'])->first();
                if($info){
                    //线下
                    if($info->is_unline == 1){
                        $re = $this->doTask($instid);
                        if(!$re){
                            $errArr[] = [
                                'instid' => $instid,
                                'errMsg' => '审批流程失败'
                            ];
                        }
                    }else{
                        $flow = FuncMediatorFlow::where([['uid',$info->id],['status',1],['type',1],['is_handle',0]])->first();
                        if($flow){
                            if($flow->rate == $number){
                                //判断是否是确认比例状态
                                if($flow->is_sure == 1){
                                    $re = $this->doTask($instid);
                                    if(!$re){
                                        $errArr[] = [
                                            'instid' => $instid,
                                            'errMsg' => '审批流程失败'
                                        ];
                                    }
                                }
                            }else{
                                //3.居间确认比例
                                $content = "您好！您在我公司申请的居间协议已通过初步审核！请您凭手机号再次登陆居间申请系统确认居间返佣比例。注：确认居间返佣比例后方可生成居间编号及居间协议，请收到此短信后务必及时登陆确认。如有疑问请及时与您的业务经理保持联系或拨打客服电话400-8820-628";
                                $this->sendSmsSingle($flow->info->phone, $content, 'JJR-KF');
                                $update['is_sure'] = 0;
                                $update['sure_time'] = '';
                                $update['rate'] = $number;
                                $update['instid'] = $instid;
                                FuncMediatorFlow::where('id',$flow->id)->update($update);
                            }
                        }else{
                            $errArr[] = [
                                'instid' => $instid,
                                'errMsg' => 'rpa未找到该流程'
                            ];
                        }
                    }
                }else{
                    $errArr[] = [
                        'instid' => $instid,
                        'errMsg' => 'rpa未找到该居间'
                    ];
                }
            }
        }elseif(-1 == $taskList){
            $errArr[] = [
                'instid' => "",
                'errMsg' => '查询任务出错'
            ];
        }
        return $errArr;
    }

    /**
     * 审批流程
     */
    public function doTask($instid)
    {
        $webservice = new webServiceController();
        $result = $webservice->doWorkAction($instid);
        $webservice->logout();
        return $result;
    }

    /**
     * 重新生成协议专用
     */
    public function getXYFile(){
        $names = [];

        foreach($names as $name){
            $info = FuncMediatorInfo::where('name',$name)->first();
            if($info){
                $file_path = storage_path().config('mediator.file_root').dirname($info->sign_img);
                $flow = FuncMediatorFlow::where('uid',$info->id)->orderBy('id','desc')->first();
                $this->getAgreementFile($flow,$file_path);
                echo $name."完成";
            }else{
                echo $name."不存在";
            }
            echo "<br/>";
        }
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
        $columns = "SFZH";
        $data = $this->getLcData($instid,$table,$columns);
        $info = FuncMediatorInfo::where('zjbh',$data['SFZH'])->first();
        if(!$info){
            //线下居间，同步到rpa
            $this->addMediator($data,0,$instid);
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
        $columns = "funcPFS_G_Decrypt(SFZH,'5a9e037ea39f777187d5c98b')SFZH,XYKSRQ,XYJSRQ";
        $data = $this->getLcData($instid,$table,$columns);
        $info = FuncMediatorInfo::where('zjbh',$data['SFZH'])->first();
        if($info){
        // 1.修改流程表状态
            $flow = FuncMediatorFlow::where([['uid',$info->id],['status',1],['is_handle',0]])->whereIn('type',[0,1])->first();
            if($flow){
                $flow->xy_date_begin = $this->crmDateFormat($data['XYKSRQ']);
                $flow->xy_date_end = $this->crmDateFormat($data['XYJSRQ']);
                $flow->save();
                $this->handleFlow($flow,'bl');
                $re = [
                    'status' => 200,
                    'msg' => "比例确认流程{$instid},数据同步成功"
                ];
            }else{
                $re = [
                    'status' => 500,
                    'msg' => "未查询到数据！"
                ];
            }
        }else{
            $re = [
                'status' => 500,
                'msg' => "未查询到数据！"
            ];
        }

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
        $columns = "funcPFS_G_Decrypt(SFZH,'5a9e037ea39f777187d5c98b')SFZH,XXYKSRQ,XXYJSRQ";
        $data = $this->getLcData($instid,$table,$columns);
        $info = FuncMediatorInfo::where('zjbh',$data['SFZH'])->first();
        if(!$info){
            //线下居间，同步到rpa
            $this->addMediator($data,1,$instid);         
        }else{
            if($info->is_unline == 1){
                //线下居间，同步到rpa
                $data['unline'] = 1;
                $this->addMediator($data,1,$instid);  
            }else{
                //线上居间
                $flow = FuncMediatorFlow::where([['uid',$info->id],['status',1],['is_handle',0]])->whereIn('type',[0,1])->first();
                if($flow){
                    $flow->xy_date_begin = $this->crmDateFormat($data['XXYKSRQ']);
                    $flow->xy_date_end = $this->crmDateFormat($data['XXYJSRQ']);
                    $flow->save();
                    $this->handleFlow($flow,'xq');
                }else{
                    $re = [
                        'status' => 500,
                        'msg' => "未查询到数据！"
                    ];
                    return $re;
                }
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

        $columns = "BH,KHYH,YHZH,LXSJ,DH,FHMC";
        $data = $this->getLcData($instid,$table,$columns);
        $res = $this->addFlow($data,'XG');

        if($res){
            $re = [
                'status' => 200,
                'msg' => "修改流程{$instid},数据同步成功"
            ];
        }else{
            $re = [
                'status' => 500,
                'msg' => "修改流程{$instid},数据同步失败"
            ];
        }
        
        return $re;
    }

    //居间注销流程
    private function jjrZXSQ($instid)
    {
        $table = 'TXCTC_LC_JJR_ZXSQ';
        $columns = "BH,XYJSRQ";
        $data = $this->getLcData($instid,$table,$columns);

        $res = $this->addFlow($data,'ZX');
        if($res){
            $re = [
                'status' => 200,
                'msg' => "注销流程{$instid},数据同步成功"
            ];
        }else{
            $re = [
                'status' => 500,
                'msg' => "注销流程{$instid},数据同步失败"
            ];
        }
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
        $columns = "funcPFS_G_Decrypt(ZJBH,'5a9e037ea39f777187d5c98b')ZJBH,QRLX";
        $data = $this->getLcData($instid,$table,$columns);
        if($data['QRLX'] == 1){
            $flow = FuncMediatorFlow::where([['zjbh',$data['ZJBH']],['status',1],['is_handle',1]])->whereIn('type',[0,1])->orderBy('id','desc')->first();
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
    private function getLcData($instid,$tabname,$columns)
    {
        //获取流程信息
        $sql = "select {$columns} from {$tabname} where instid = ".$instid;
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
        copy($old_sign_img,$root.$new_sign_img);
        copy($old_sfz_zm_img,$root.$new_sfz_zm_img);
        copy($old_sfz_fm_img,$root.$new_sfz_fm_img);
        copy($old_sfz_sc_img,$root.$new_sfz_sc_img);
        copy($old_bank_img,$root.$new_bank_img);
        if($flow->is_exam == 1){
            copy($old_exam_img,$root.$new_exam_img);
        }

        //修改数据库
        $flow->sign_img = $new_sign_img;
        $flow->sfz_zm_img = $new_sfz_zm_img;
        $flow->sfz_fm_img = $new_sfz_fm_img;
        $flow->sfz_sc_img = $new_sfz_sc_img;
        $flow->bank_img = $new_bank_img;
        if($flow->is_exam == 1){
            $flow->exam_img = $new_exam_img;
        }
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

        //根据地址长度判断公章位置
        $y = 55;
        $address = $flow->address;
        $len = mb_strlen($address);


        $sum = 0;
        for($i = 0;$i < $len; $i++) {
            $item = mb_substr($address, $i, 1);
            if(strlen($item) == 3) {
                $l = 2;
            } else {
                $l = 1;
            }
            $sum += $l;
        }
        if($sum > 32){
            $y = 80;
        }

        $mpdf->SetWatermarkImage('images/Mediator/gz.png','0.7',['50','50'],['30',$y]);
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
        $mpdf->SetWatermarkImage('images/Mediator/gz.png','0.7',['50','50'],['30','150']);
        $mpdf->showWatermarkImage = true;

        $name = $flow->info->name."--居间水印协议.pdf";
        $mpdf->Output($path."/".$name);
    }

    /**
     * 同步流程数据到主表
     * @param $flow,$is_crm
     * @return mixed
     */
    private function syncToInfo($flow,$is_crm = 0)
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
        if($flow->type == 0 && $is_crm == 0){
            $update['open_time'] = date('Y-m-d');
        }

        return FuncMediatorInfo::where('id',$flow->uid)->update($update);
    }


    /**
     * 办理流程
     */
    public function handleFlow($flow,$type)
    {
//      1.修改流程表状态
        $flow->is_handle = 1;
        $flow->handle_time = date('Y-m-d H:i:s');
        $flow->save();
//      2.移动文件
        $path = $this->moveFile($flow);
//      3.生成协议
        $this->getAgreementFile($flow,$path);
//      4.同步数据到主表
        $this->syncToInfo($flow);
//      5.发送短信
        $info = FuncMediatorInfo::where('id',$flow->uid)->first();
        if($type == 'bl'){
            $content = "您好！您在我公司申请的居间协议已办理成功！客户经理号为". $info->manager_number ."，居间编号为". $info->number ."。如有疑问请及时与您的业务经理保持联系或拨打客服电话400-8820-628";
        }else{
            $content = "您好！您在我公司申请的居间续签协议已办理成功！如有疑问请及时与您的业务经理保持联系或拨打客服电话400-8820-628";
        }
        $this->sendSmsSingle($info->phone, $content, 'JJR-KF');
//      6.加入回访
        $this->addReview($flow);
    }
    
    /**
     * 线下居间新增
     * @param $data
     */
    private function addMediator($data,$type,$instid)
    {
//        获取居间人完整信息
        $post_data = [
            'type' => 'jjr',
            'action' => 'getJjrBy',
            'param' => [
                'table' => 'JJR',
                'by' => [
                    ['SFZH','=',$data['SFZH']]
                ]
            ]
        ];
        $result = $this->getCrmData($post_data);
        $jjr = end($result);
        if(isset($data['unline']) && $data['unline'] == 1){
            $info = FuncMediatorInfo::where('zjbh',$data['SFZH'])->first();
        }else{
            //        增加主表
            $add1 = [
                'name' =>  $jjr['XM'],
                'phone' =>  $jjr['LXSJ'] ? $jjr['LXSJ'] : $jjr['DH'],
                'zjbh' =>  $jjr['SFZH'],
                'is_unline' => 1,
                'open_time' =>  $this->crmDateFormat($jjr['KHRQ']),
            ];
            $info = FuncMediatorInfo::create($add1);
        }

        //        增加流程表
        //          获取比例
        if($type==0){
            $param = [
                'type' => 'jjr',
                'action' => 'getRateByBh',
                'param' => [
                    'bh' => $jjr['BH'],
                ]
            ];
        }else{
            $param = [
                'type' => 'jjr',
                'action' => 'getRateByInstanceId',
                'param' => [
                    'instid' => $instid,
                ]
            ];
        }
        $res = $this->getCrmData($param);
        if(200 == $res['code']){
            $rate = $res['data'];
        }else{
            $rate = "";
        }
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
            'edu_background' => $this->getDm('JJR_XL',$jjr['XL']),
            'address' => $jjr['LXDZ'],
            'postal_code' => $jjr['YZBM'],
            'profession' => $jjr['ZY'],
            'is_exam' => 0,
            'exam_number' => '',
            'rate' => $rate,
            'jr_rate' => '',
            'part_b_date' => $this->crmDateFormat($jjr['XYKSRQ']),
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
            'handle_time' => date('Y-m-d H:i:s'),
            'is_review' => 1,
            'status' => 1,
            'crmflow_end_time' => date('Y-m-d H:i:s'),
        ];
        $flow = FuncMediatorFlow::create($add2);
        //同步信息到主表
        $this->syncToInfo($flow,1);
        //加入回访
        $this->addReview($flow);
    }

    /**
     * 添加流程
     * @param $data
     * @param $type
     */
    private function addFlow($data,$type)
    {
        $info = FuncMediatorInfo::where('number',$data['BH'])->first();

        if(!$info){
            return false;
        }

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
            $name = [
                'phone' => '手机号',
                'bank_number' => '银行卡号',
                'bank_name' => '银行名称',
                'bank_branch' => '支行名称'
            ];
            foreach ($update as $k => $v){
                $add = [
                    'fid' => $flow->id,
                    'name' => $name[$k],
                    'old' => $info->$k,
                    'new' => $v
                ];
                FuncMediatorChangeList::create($add);
            }
        }
        return true;
    }

    /**
     * 居间加入回访
     */
    private function addReview($flow)
    {
        $info = FuncMediatorInfo::where('id',$flow->uid)->first();
        $dept = Sysdept::where('id',$flow->dept_id)->first();
        if($flow->is_review == 1){

            //根据客户经理编号获取客户经理姓名
            $post_data = [
                'type' => 'common',
                'action' => 'getEveryBy',
                'param' => [
                    'table' => 'YGXX',
                    'by' => [
                        ['BH','=',$flow->manager_number]
                    ],
                    'columns' => ['XM']
                ]
            ];
            $res = $this->getCrmData($post_data);
            if(isset($res[0])){
                $manager_name = $res[0]['XM'];
            }else{
                $manager_name = "";
            }

            $data = [
                'deptname' => $dept->name,
                'mediatorname' => $info->name,
                'sex' => $flow->sex,
                'manager_name' => $manager_name,
                'managerNo' => $flow->manager_number,
                'rate' => $flow->rate,
                'tel' => $info->phone,
                'open_date' => $info->open_time,
                'is_dist' => 0,
                'number' => $flow->number
            ];
            rpa_jjrvis::create($data);
        }
    }

    /**
     * 格式化crm日期
     * @param $date
     * @return string
     */
    public function crmDateFormat($date)
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
        $sql = "select NOTE from txtdm where FLDM ='".$type ."' and CBM =".$val;
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


    /**
     * 同步居间人培训时长
     * @param Request $request
     * @return array
     */
    public function syncMediatorTrainingDuration(Request $request){
        $param = [
            'type' => 'jjr',
            'action' => 'getPart',
            'param' => [
                
            ]
        ];
        $list = $this->getCrmData($param);
        if(!$list) {
            return [
                'status' => 200,
                'msg' => '没有更多的数据'
            ];
        }
        $data = [];
        foreach ($list as $k => $v) {
            $data[] = [
                'begintime' => strtotime($v['XYKSRQ']),
                'endtime' => strtotime($v['XYJSRQ']),
                'name' => $v['XM'],
                'number' => $v['BH'],
            ];
        }
        $guzzle = new Client();
        $response = $guzzle->post('http://api.hatzjh.com/live/getmedinfo',[
            'query' => [
                'username' => 'haqhJJCX',
                'password' => 'JJCXMediator',
                '_time' => 1,
            ],
            'form_params' => [
                'data' => json_encode($data)
            ]
        ]);
        $body = $response->getBody();
        $result = json_decode((string)$body,true);
        if(!$result) {
            return [
                'status' => 500,
                'msg' => '获取居间培训时长接口异常'
            ];
        }
        if(isset($result['code'])) { // 表示有问题
            return [
                'status' => 500,
                'msg' => "获取居间培训时长接口异常:".$result['msg']
            ];
        }
        $crmData = [];
        foreach ($data as $k => $v) {
            $time = 200 == $result[$k]['code']?$result[$k]['total_time']:0;
            $crmData[] = [
                'name' => $v['name'],
                'number' => $v['number'],
                'time' =>$time?round($time/3600, 2):0
            ];
        }
        $param = [
            'type' => 'jjr',
            'action' => 'SyncTrainingDuration',
            'param' => [
                'date' => date('Ymd'),
                'data' => json_encode($crmData)
            ]
        ];
        $crmResult = $this->getCrmData($param);
        if(isset($crmResult['code']) && 200 == $crmResult['code']) {
            return [
                'status' => 200,
                'msg' => '同步成功'
            ];
        } else {
            return [
                'status' => 500,
                'msg' => '同步失败'
            ];
        }
    }
}