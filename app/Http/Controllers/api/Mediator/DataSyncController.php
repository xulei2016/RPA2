<?php

namespace App\Http\Controllers\api\Mediator;

use App\Http\Controllers\api\BaseApiController;
use App\Models\Admin\Base\Organization\SysDept;
use App\Models\Index\Mediator\FuncMediatorFlow;
use App\Models\Index\Mediator\FuncMediatorInfo;
use App\Models\Index\Mediator\FuncMediatorInfoCopy;
use App\Models\Index\Mediator\FuncMediatorFlowCopy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataSyncController extends BaseApiController
{
    /**
     * 同步居间数据，仅手动调用
     */
    public function mediator_data(Request $request)
    {
        set_time_limit(0);
        $this->MultipleMediator();die;
        //处理部门
        // $this->updateDept();exit;

        //1.将内部系统正常数据导入rpa主表和流程表
        //$this->SingleMediator();
        //2.将内部系统重复的数据同步到流程表
        //$this->MultipleMediator();
        //3.将crm的居间数据更新到rpa主表
        // $this->CrmMediator();
        //4.增加变更履历
        // $list = FuncMediatorInfo::where([['id', '<=', '7443']])->select(['id','birthday','open_time'])->get()->toArray();
        // $error = [];
        // foreach($list as $k => $v) {
        //     $birthday = $v['birthday'];
        //     if($birthday) {
        //         $birthday = str_replace('.','-',$birthday);
        //         $birthday = str_replace('—','-',$birthday);
        //         $birthday = str_replace('–','-',$birthday);
        //         $birthday = str_replace('-','-',$birthday);
        //         $birthday = str_replace('ˉ','-',$birthday);
        //         $birthday = str_replace(' ','-',$birthday);
        //         $birthday = str_replace('?','-',$birthday);
        //         $birthday = str_replace('年','-',$birthday);
        //         $birthday = str_replace('月','-',$birthday);
        //         $birthday = str_replace('日','',$birthday);
        //         $birthday = str_replace('号','',$birthday);
        //         $strResult = strtotime($birthday);
        //         if($strResult) { //能被转换
        //             FuncMediatorInfo::where("id", $v['id'])
        //             ->update(['birthday' => date('Y-m-d', $strResult)]);

        //             FuncMediatorFlow::where("uid", $v['id'])
        //             ->update(['birthday' => date('Y-m-d', $strResult)]);
        //         } else {
        //             $error[] = [
        //                 'id' => $v['id'],
        //                 'birthday' => $birthday
        //             ];
        //         }
        //     } else {

        //     }
        //     // $birthday = $this->findNum($v['birthday']);
        //     // $len = strlen($birthday);
        //     // if($len != 8) {
        //     //     if($len == 6) { // xxxx x x

        //     //     } elseif($len == 7) {

        //     //     }
        //     //     $error[] = [
        //     //         'id' => $v['id'],
        //     //         'birthday' => $birthday
        //     //     ];
        //     // } 
        // }
        // print_r($error);die;


    }

    public function findNum($str=''){ 
        $str=trim($str); 
        if(empty($str)){return '';} 
        $temp=array('1','2','3','4','5','6','7','8','9','0'); 
        $result=''; 
        for($i=0;$i<strlen($str);$i++){ 
            if(in_array($str[$i],$temp)){ 
                $result.=$str[$i]; 
            } 
        } 
        return $result; 
    } 

    private function other(){
        $arr = [
            // '310110196410097035',
            // '320321199509204019',
            // '340824198512072817',
            // '341282198912040154',
            // '342401198804268577',
            // '370122195711063906',
            // '370481198610155713',
            // '411303198912153202',
            // '420703198910153359',
            // '433029198110090417',
            // '441224198608216610',
            // '441424197004145782',
            // '450325199003252210',
            // '622425199003112313',
//            '512322197007021284'
        ];
        foreach($arr as $v){
            //去主表查询该身份证号码
            $info = FuncMediatorInfo::where('zjbh',$v)->first();
            //内部系统
            $sql = "select * from oa_mediator where sfz_number ='".$v."' GROUP BY mediatorname HAVING COUNT(mediatorname) = 1";
            $res = DB::connection("oa")->select($sql);
            if($res){
                foreach($res as $vv){
                    $vv = json_decode(json_encode($vv),true);
                    if($vv['mediatorname'] != $info->name){
                        if($info->is_unline != 1){
                            $vv['sfz_number'] = $vv['sfz_number']."**";
                        }
                        $data = $this->getInfoData($vv);
                        if($info->is_unline != 1){
                            $data['is_unline'] = 1;
                        }
                        $res1 = FuncMediatorInfo::create($data);
                        $vv['uid'] = $res1->id;
                        $data = $this->getFlowData($vv);
                        // // print_r($data);exit;
                        $re = FuncMediatorFlow::create($data);
                    }else{
                        if($info->is_unline == 1){
                            $up['zjbh'] = $info->zjbh."**";
                            FuncMediatorInfo::where('id',$info->id)->update($up);
                            FuncMediatorFlow::where('uid',$info->id)->update($up);
                        }
                    }
                }
            }
        }
    }

    /*********************数据处理***********************/
    public function updateDept()
    {
        $info = FuncMediatorInfo::where('dept_id',0)->get();
        $i=1;
        foreach($info as $v){
            echo $i;
            $sql = "select did from oa_mediator where sfz_number ='".$v->zjbh."'";
            //echo $sql;exit;
            $res = DB::connection("oa")->select($sql);
            //获取部门名称
            $sql = "select deptname from oa_dept where did=".$res[0]->did;
            $res = DB::connection("oa")->select($sql);
            // print_r($res);exit;
            $dept = SysDept::where('name',$res[0]->deptname)->first();
            if($dept){
                $update['dept_id'] = $dept->id;
                FuncMediatorInfo::where('id',$v->id)->update($update);
                FuncMediatorFlow::where('uid',$v->id)->update($update);
            }else{
                print_r("失败".$res[0]);
            }
            $i++;
        }
    }

    /**
     * 同步crm协议开始日期
     * 已运行
     */
    public function syncXyDateBegin(Request $request)
    {
        set_time_limit(0);
        //找到需要更新的居间
        $flow = FuncMediatorFlow::where([['handle_time','>=','2020-03-23 17:00:00'],['type',0],['id','>',8025]])->get();
        $i=0;
        foreach($flow as $v){
            $i++;
            //去crm获取最新的协议开始日期
            //获取居间人完整信息
            $post_data = [
                'type' => 'jjr',
                'action' => 'getJjrBy',
                'param' => [
                    'table' => 'JJR',
                    'by' => [
                        ['SFZH','=',$v->zjbh]
                    ]
                ]
            ];
            $result = $this->getCrmData($post_data);
            $jjr = $result[0];
            $update = [
                'xy_date_begin' => $this->crmDateFormat($jjr['XYKSRQ'])
            ];
            FuncMediatorFlow::where('id',$v->id)->update($update);
            //重新生成协议
            $v->xy_date_begin = $this->crmDateFormat($jjr['XYKSRQ']);
            $path = storage_path().config('mediator.file_root').dirname($v->sign_img);
            $this->getAgreementFile($v,$path);
            echo "第".$i."个：ID-".$v->id;
            echo "\r\n";
        }
    }

    /*********************内部系统***************************/

    //单条数据处理
    private function SingleMediator()
    {
        $sql = "select * from oa_mediator where step=8 and sfz_number != '' GROUP BY sfz_number HAVING COUNT(sfz_number)= 1 order by id limit 2000,2000";
        $res = DB::connection("oa")->select($sql);
        $i = 2001;
        foreach($res as $v){
            //写入主表
            $v = json_decode(json_encode($v),true);
            $this->setLog($v,"第".$i."个开始");
            // print_r($v);exit;
            $this->getFile($v);
            $data = $this->getInfoData($v);

            // print_r($data);exit;
            $res = FuncMediatorInfo::create($data);
            if($res){
                $this->setLog($v,'单条写入主表成功!');
                //写入流程表
                $v['uid'] = $res->id;
                $data = $this->getFlowData($v);
                // print_r($data);exit;
                $re = FuncMediatorFlow::create($data);
                if($re){
                    $this->setLog($v,'单条写入流程表成功！');
                }else{
                    $this->setLog($v,'单条写入流程表失败！');
                }
            }else{
                $this->setLog($v,'单条写入主表失败!');
            }
            $this->setLog($v,"结束\r\n");
            $i++;
        }
    }

    //多条记录处理
    private function MultipleMediator()
    {
        $sql = "select * from oa_mediator where mediatorname ='傅明英'";
        $res = DB::connection("oa")->select($sql);
        $i = 1;
        foreach($res as $v){
            //写入主表
            $v = json_decode(json_encode($v),true);
            $this->setLog($v,"第".$i."个开始");
            $this->getFile($v);
            $data = $this->getInfoData($v);
            $res = FuncMediatorInfo::create($data);
            if($res){
                $this->setLog($v,'多条写入主表成功!');
                $uid = $res->id;
                $sql1 = "select * from oa_mediator where mediatorname ='傅明英' order by xy_date_end asc";
                $res1 = DB::connection("oa")->select($sql1);
                foreach($res1 as $v1){
                    $v1 = json_decode(json_encode($v1),true);
                    if($v1['isCheck'] == 1){
                        $v1['uid'] = $uid;
                        $data = $this->getFlowData($v1);
                        $re = FuncMediatorFlow::create($data);
                        if($re){
                            $this->setLog($v,'多条写入流程表成功!');
                            //更新主表
                            $data= $this->getInfoData($v1);
                            FuncMediatorInfo::where('id',$uid)->update($data);
                        }else{
                            $this->setLog($v,'多条写入流程表失败!');
                        }
                    }
                }
            }else{
                $this->setLog($v,'多条写入主表失败!');
            }
            $this->setLog($v,"结束\r\n");
            $i++;
            exit;
        }
    }
    /**
     * 生成主表数据
     * @param $arr
     * @return array
     */
    private function getInfoData($arr)
    {
        $status = 1;
        if(strtotime($arr['xy_date_end']) - time() < 0){
            $status = 2;
        }

        //判断是否办理成功
        if($arr['isHandle'] == 1){
            $imgPath = "/居间人影像/";
        }else{
            $status = 0;
            $imgPath = "";
        }
        $data = [
            'name' => $arr['mediatorname'],
            'zjbh' => $arr['sfz_number'],
            'phone' => $arr['tel'],
            'sex' => $arr['sex'],
            'birthday' => $arr['brith'],
            'open_time' => $arr['open_date'],
            'dept_id' => $this->getDeptId($arr['did']),
            'manager_number' => $arr['managerNo'] == '' ? null : $arr['managerNo'],
            'number' => $arr['number'],
            'xy_date_end' => $arr['xy_date_end'],
            'email' => $arr['email'],
            'is_exam' => $arr['isoccupa'],
            'edu_background' => $arr['edubackground'],
            'sign_img' => $imgPath.$arr['signatureimg'],
            'address' => $arr['address'],
            'postal_code' => $arr['postalcode'],
            'profession' => $arr['profession'],
            'rate' => $arr['rate'],
            'jr_rate' => $arr['jrrate'],
            'sfz_zm_img' => $imgPath.$arr['sfz_img_zm'],
            'sfz_fm_img' => $imgPath.$arr['sfz_img_fm'],
            'sfz_sc_img' => $imgPath.$arr['sfz_img_sc'],
            'sfz_date_end' => $arr['sfz_date_end'],
            'sfz_address' => $arr['sfz_address'],
            'bank_number' => $arr['bank_number'],
            'bank_name' => $arr['bank_name'],
            'bank_branch' => $arr['bank_branch'],
            'bank_username' => $arr['bank_accountname'],
            'bank_img' => $imgPath.$arr['bank_img'],
            'status' => $status
        ];

        return $data;
    }

    /**
     * 生成流程表数据
     * @param $arr
     * @return array
     */
    private function getFlowData($arr)
    {
        //判断是否办理成功
        if($arr['isHandle'] == 1){
            $imgPath = "/居间人影像/";
        }else{
            $imgPath = "";
        }

        $xy_date_start = strtotime("+1 day",strtotime($arr['xy_date_end']));
        $xy_date_start = date("Y-m-d",strtotime("-1 year",$xy_date_start));
        $xy_date_begin = $arr['open_date'] ? $arr['open_date'] : $xy_date_start;

        $data = [
            'uid' => $arr['uid'],
            'type' => $this->getType($arr),
            'dept_id' => $this->getDeptId($arr['did']),
            'zjbh' => $arr['sfz_number'],
            'manager_number' => $arr['managerNo'] == '' ? null : $arr['managerNo'],
            'number' => $arr['number'],
            'sex' => $arr['sex'],
            'birthday' => $arr['brith'],
            'email' => $arr['email'],
            'edu_background' => $arr['edubackground'],
            'sign_img' => $imgPath.$arr['signatureimg'],
            'address' => $arr['address'],
            'postal_code' => $arr['postalcode'],
            'profession' => $arr['profession'],
            'is_exam' => $arr['isoccupa'],
            'rate' => $arr['rate'],
            'jr_rate' => $arr['jrrate'],
            'xy_date_begin' => $xy_date_begin,
            'xy_date_end' => $arr['xy_date_end'],
            'part_b_date' => $xy_date_begin,
            'xy_location' => $arr['xyszd'],
            'sfz_zm_img' => $imgPath.$arr['sfz_img_zm'],
            'sfz_fm_img' => $imgPath.$arr['sfz_img_fm'],
            'sfz_sc_img' => $imgPath.$arr['sfz_img_sc'],
            'sfz_date_end' => $arr['sfz_date_end'],
            'sfz_address' => $arr['sfz_address'],
            'bank_number' => $arr['bank_number'],
            'bank_name' => $arr['bank_name'],
            'bank_branch' => $arr['bank_branch'],
            'bank_username' => $arr['bank_accountname'],
            'bank_img' => $imgPath.$arr['bank_img'],
            'from' => 0,
            'remark' => $arr['remark'],
            'step' => 1000,
            'is_check' => $arr['isCheck'],
            'is_sure' => $arr['isFirst'],
            'is_handle' => $arr['isHandle'],
            'status' => 1,
            'crmflow_end_time' => '',
        ];
        return $data;
    }

    /**
     * 获取部门id
     * @param $did
     * @return int
     */
    private function getDeptId($did)
    {
        $sql = "select deptname from oa_dept where did=".$did;
        $res = DB::connection("oa")->select($sql);
        $dept_id = 0;
        if($res){
            $deptname = $res[0]->deptname;
            $dept = SysDept::where("name",$deptname)->first();
            if($dept){
                $dept_id = $dept->id;
            }
        }

        return $dept_id;
    }

    /**
     * 获取影像
     * @param $arr
     */
    private function getFile($arr)
    {
        if($arr['signatureimg'] == '' || strpos($arr['signatureimg'],'temp') !== false){
            $this->setLog($arr,"没有图片路劲！");
            return false;
        }
        $path = dirname(dirname($arr['signatureimg']))."/";
        $root_path = "D:/mediatorFile/";

        $aim ="D:/projects/居间人影像/";
        if(is_dir($root_path.$path)){
            $re = $this->moveDir($root_path.$path,$aim.$path);
            if($re){
                $this->setLog($arr,"文件移动成功！");
            }else{
                $this->setLog($arr,"文件移动失败！");
            }
        }else{
            $this->setLog($arr,"文件不存在！");
        }
    }

    /**
     * 获取类型
     * @param $arr
     * @return int
     */
    private function getType($arr)
    {
        if($arr['open_date']){
            $xy_date_start = strtotime("-1 year",strtotime($arr['xy_date_end']));
            if($xy_date_start - strtotime($arr['open_date']) >= 0){
                $type = 1;
            }else{
                $type = 0;
            }
        }else{
            $type = 0;
        }
        
        return $type;
    }

    /***************************crm*************************/

    public function pageT(){
        $rows = 150; // 每页条数
        $page = 24; // 页数
        $start = ($page-1)*$rows;
        $end = $rows*$page;
        $table = "TXCTC_JJR";
        $sqlData = "SELECT C.*,funcPFS_G_Decrypt(C.SFZH,'5a9e037ea39f777187d5c98b')SFZH,funcPFS_G_Decrypt(C.JZDZ,'5a9e037ea39f777187d5c98b')JZDZ,funcPFS_G_Decrypt(C.LXSJ,'5a9e037ea39f777187d5c98b')LXSJ,funcPFS_G_Decrypt(C.DH,'5a9e037ea39f777187d5c98b')DH,funcPFS_G_Decrypt(C.EMAIL,'5a9e037ea39f777187d5c98b')EMAIL,funcPFS_G_Decrypt(C.LXDZ,'5a9e037ea39f777187d5c98b')LXDZ FROM (SELECT A.*, rownum r FROM (select * from {$table} where ZHZT <>2 order by ID asc) A  where rownum <= {$end}) C WHERE r > {$start}";
        return $sqlData;
    }

    private function CrmMediator()
    {
        $sqlData = $this->pageT();
        $post_data = [
            'type' => 'jjr',
            'action' => 'getJjrBy',
            'param' => [
                'table' => 'JJR',
                'by' => $sqlData
            ]
        ];
        $res = $this->getCrmData($post_data);
        $i = 3451;
        foreach($res as $v){
            $this->setLog($v,"第".$i."个开始",1);
            $info = FuncMediatorInfo::where('zjbh',$v['SFZH'])->first();
            if($info){
                $data = $this->getInfoData2($v);
                $res = FuncMediatorInfo::where('zjbh',$v['SFZH'])->update($data);
                if($res){
                    $this->setLog($v,'crm流程更新成功！',1);
                }else{
                    $this->setLog($v,'crm流程更新失败！',1);
                }
            }else{
                $this->setLog($v,'rpa主表不存在该数据！',1);
                //新增
                $data = $this->getInfoData2($v);
                $data['zjbh'] = $v['SFZH'];
                if($v['ZHZT'] != 1){
                    $data['status'] = 1;
                }
                $res = FuncMediatorInfo::create($data);
                if($res){
                    $this->setLog($v,'主表新增成功！',1);
                    
                    $data = $this->getFlowData2($v);
                    $data['uid'] = $res->id;
                    $re = FuncMediatorFlow::create($data);
                    if($re){
                        $this->setLog($v,'流程表新增成功！',1);
                    }else{
                        $this->setLog($v,'流程表新增失败！',1);
                    }
                }else{
                    $this->setLog($v,'主表新增失败！',1);
                }
            }
            $this->setLog($v,"结束\r\n",1);
            $i++;
        }
    }

    private function getInfoData2($arr)
    {
        $dept = $this->getDeptId2($arr['YYB']);
        if($dept === false){
            $this->setLog($arr,"营业部ID:".$arr['YYB']."未找到对应部门！",1);
            $dept = 0;
        }

        $data = [
            'name' => $arr['XM'],
            'phone' => $arr['LXSJ'] ? $arr['LXSJ'] : $arr['DH'],
            'sex' => $this->getDm('SEX',$arr['XB']),
            'dept_id' => $dept,
            'open_time' => $this->crmDateFormat($arr['KHRQ']),
            'manager_number' => $this->getManagerNumber($arr['KFR']),
            'number' => $arr['BH'],
            'xy_date_end' => $this->crmDateFormat($arr['XYJSRQ']),
            'email' => $arr['EMAIL'],
            'edu_background' => $this->getDm('XLDM',$arr['XL']),
            'address' => $arr['LXDZ'],
            'postal_code' => $arr['YZBM'],
            'profession' => $arr['ZY'],
            'rate' => $arr['TCFAMC'],
            'sfz_date_end' => $this->crmDateFormat($arr['ZJDQ']),
            'sfz_address' => $arr['JZDZ'],
            'bank_number' => $arr['YHZH'],
            'bank_name' => $arr['KHYH'],
            'bank_branch' => $arr['FHMC'],
            'bank_username' => $arr['XM'],
        ];

        if($arr['ZHZT'] == 1){
            $data['status'] = 3;
        }

        return $data;
    }

    private function getFlowData2($arr)
    {
        $dept = $this->getDeptId2($arr['YYB']);
        if($dept === false){
            $this->setLog($arr,"营业部ID:".$arr['YYB']."未找到对应部门！",1);
            $dept = 0;
        }
        $data = [
            'type' => 1,
            'dept_id' => $dept,
            'zjbh' => $arr['SFZH'],
            'manager_number' => $this->getManagerNumber($arr['KFR']),
            'number' => $arr['BH'],
            'sex' => $this->getDm('SEX',$arr['XB']),
            'birthday' => '',
            'email' => $arr['EMAIL'],
            'edu_background' => $this->getDm('XLDM',$arr['XL']),
            'address' => $arr['LXDZ'],
            'postal_code' => $arr['YZBM'],
            'profession' => $arr['ZY'],
            'is_exam' => 0,
            'exam_number' => '',
            'rate' => $arr['TCFAMC'],
            'jr_rate' => '',
            'part_b_date' => $this->crmDateFormat($arr['XYKSRQ']),
            'xy_date_begin' => $this->crmDateFormat($arr['XYKSRQ']),
            'xy_date_end' => $this->crmDateFormat($arr['XYJSRQ']),
            'sfz_date_end' => $this->crmDateFormat($arr['ZJDQ']),
            'sfz_address' => $arr['JZDZ'],
            'bank_number' => $arr['YHZH'],
            'bank_name' => $arr['KHYH'],
            'bank_branch' => $arr['FHMC'],
            'bank_username' => $arr['XM'],
            'from' => 3,
            'remark' => $arr['BZ'],
            'is_check' => 1,
            'is_sure' => 1,
            'is_handle' => 1,
            'status' => 1,
        ];

        return $data;
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
    private function getDeptId2($yyb)
    {
        $dept = SysDept::where('yyb_hs',$yyb)->orWhere('khfz_hs',$yyb)->first();
        if(isset($dept->id)){
            $dept = $dept->id;
        }else{
            $dept = false;
        }
        return $dept;
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


    /**********************其他*********************/
    /**
     * 日志
     * @param $arr
     * @param $data
     */
    private function setLog($arr,$data,$is_crm = 0)
    {
        $file = "D:/log.txt";
        if($is_crm == 1){
            $content = "CRM表ID：".$arr['ID']."身份证号码：".$arr['SFZH']."，".$data;
        }else{
            $content = "居间人ID：".$arr['id']."，身份证号码：".$arr['sfz_number'].",".$data;
        }
        file_put_contents($file,$content.PHP_EOL,FILE_APPEND);
    }

    /**
     * 移动文件夹
     *
     * @param string $oldDir
     * @param string $aimDir
     * @param boolean $overWrite 该参数控制是否覆盖原文件
     * @return boolean
     */
    private function moveDir($oldDir, $aimDir, $overWrite = false) {
        $aimDir = str_replace('', '/', $aimDir);
        $aimDir = substr($aimDir, -1) == '/' ? $aimDir : $aimDir . '/';
        $oldDir = str_replace('', '/', $oldDir);
        $oldDir = substr($oldDir, -1) == '/' ? $oldDir : $oldDir . '/';
        if (!is_dir($oldDir)) {
            return false;
        }
        if (!file_exists($aimDir)) {
            $this->createDir($aimDir);
        }
        @ $dirHandle = opendir($oldDir);
        if (!$dirHandle) {
            return false;
        }
        while (false !== ($file = readdir($dirHandle))) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if (!is_dir($oldDir . $file)) {
                $this->moveFile($oldDir . $file, $aimDir . $file, $overWrite);
            } else {
                $this->moveDir($oldDir . $file, $aimDir . $file, $overWrite);
            }
        }
        closedir($dirHandle);
        return rmdir($oldDir);
    }

    /**
     * 建立文件夹
     *
     * @param string $aimUrl
     * @return void
     */
    private function createDir($aimUrl) {
        $aimUrl = str_replace('', '/', $aimUrl);
        $aimDir = '';
        $arr = explode('/', $aimUrl);
        $result = true;
        foreach ($arr as $str) {
            $aimDir .= $str . '/';
            if (!file_exists($aimDir)) {
                $result = mkdir($aimDir);
            }
        }
        return $result;
    }

    /**
     * 移动文件
     *
     * @param string $fileUrl
     * @param string $aimUrl
     * @param boolean $overWrite 该参数控制是否覆盖原文件
     * @return boolean
     */
    private function moveFile($fileUrl, $aimUrl, $overWrite = false) {
        if (!file_exists($fileUrl)) {
            return false;
        }
        if (file_exists($aimUrl) && $overWrite = false) {
            return false;
        }elseif (file_exists($aimUrl) && $overWrite = true) {
            $this->unlinkFile($aimUrl);
        }
        $aimDir = dirname($aimUrl);
        $this->createDir($aimDir);
        rename($fileUrl, $aimUrl);
        return true;
    }

    /**
     * 删除文件
     *
     * @param string $aimUrl
     * @return boolean
     */
    private function unlinkFile($aimUrl) {
        if (file_exists($aimUrl)) {
            unlink($aimUrl);
            return true;
        } else {
            return false;
        }
    }

}