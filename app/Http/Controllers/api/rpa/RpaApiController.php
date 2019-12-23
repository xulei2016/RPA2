<?php

namespace App\Http\Controllers\api\rpa;

use App\Http\Controllers\Admin\Func\Contract\PublishController;
use App\Http\Controllers\api\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;
use App\Models\Admin\Base\SysConfig;
use App\Models\Admin\Rpa\rpa_immedtasks;
use App\Models\Admin\Rpa\rpa_timetasks;
use App\Models\Admin\Rpa\rpa_clock_list;
use App\Models\Admin\Api\RpaDtuSms;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\Func\RpaSimulationAccount;
use App\Models\Admin\Func\rpa_customer_jkzx; 

class RpaApiController extends BaseApiController
{
    /**
     * RPA服务器打卡接口
     * @param Request $request
     * @return  [Integer] $code  状态码
     *@return  [String]   $msg    状态信息
     */
    public function punch_card(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'CARD_IM' => 'required|numeric',
            'CARD_TM' => 'required|numeric',
            'CARD_EX' => 'required|numeric',
            'CARD_RT' => 'required|numeric',
            'CPU_sum' => 'required',
            'Memory_mem' => 'required',
            'Disks_mem' => 'required',
            'Process_p' => 'required|numeric',
            'Process_e' => 'required|numeric',
        ]);

        //删除历史打卡记录
        //获取上次删除时间
        $file_path = public_path()."/punch_card.txt";
        $del_date = "";
        if(file_exists($file_path)){
            $del_date = file_get_contents($file_path);
        }
        //如果删除时间不是今天，需要删除
        if(date("Y-m-d",strtotime($del_date)) != date("Y-m-d")){
            //获取保留打卡天数
            $limit = $this->get_config(['punch_card_limit']);
            rpa_clock_list::where("created_at","<",strtotime("- ".$limit['punch_card_limit']." days"))->destory();

            file_put_contents($file_path,date("Y-m-d"));
        }


        $ip = $request->getClientIp();
        //获取服务器信息
        if (!Cache::has("sysConfigs")) {
            $sysConfigs = SysConfig::get();
            if($sysConfigs){
                Cache::add("sysConfigs",$sysConfigs,3600);
            }
        }else{
            $sysConfigs = Cache::get("sysConfigs");
        }
        foreach($sysConfigs as $config){
            if($config->item_value == $ip){
                $host = $config->item_key;
            }
        }
        if(!isset($host)){
            return response()->json(['status'=>$ip,'msg'=> "非rpa服务器无法调用该接口！"]);
        }
        //往rpa数据库打卡
        $data = [
            'host' => $host,
            'CARD_IM' => $request->CARD_IM,
            'CARD_TM' => $request->CARD_TM,
            'CARD_EX' => $request->CARD_EX,
            'CARD_RT' => $request->CARD_RT,
            'CPU_sum' => $request->CPU_sum,
            'Memory_mem' => $request->Memory_mem,
            'Disks_mem' => $request->Disks_mem,
            'Process_p' => $request->Process_p,
            'Process_e' => $request->Process_e,
            'sysparameter' => '',
            'created_at' => time()
        ];
        $re = rpa_clock_list::create($data);

        //主服务器往官网打卡
        if($host == 'H1_inner' || $host == 'H2_inner'){
            $guzzle = new Client();
            $response = $guzzle->post('http://172.16.191.26/rpa/clock/index.php',[
                'form_params' => [
                    'host' => $host,
                    'CARD_IM' => $request->CARD_IM,
                    'CARD_TM' => $request->CARD_TM,
                    'CARD_EX' => $request->CARD_EX,
                    'CARD_RT' => $request->CARD_RT,
                    'CPU_sum' => $request->CPU_sum,
                    'Memory_mem' => $request->Memory_mem,
                    'Disks_mem' => $request->Disks_mem,
                    'Process_p' => $request->Process_p,
                    'Process_e' => $request->Process_e,
                    'sysparameter' => '',
                ],
            ]);
        }
        $re = [
            'status' => 200,
            'msg' => '打卡成功'
        ];
        return response()->json($re);
    }

    /**
     * 获取打卡信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_card(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res === true){
            return response()->json($res);
        }
        $body = $this->getCard();

        $re = [
            'status'=>$body['code'],
            'msg'=> $body['info']
        ];
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);

    }

    /**
     * 发布任务
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function release_task(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        //time存在代表定时任务，不存在代表立即任务
        if(isset($request->time)){
            $data = [
                'time' => $request->time,
                'name' => $request->name,
                'jsondata' => $request->jsondata,
                'server' => $request->serverx
            ];
            $res = rpa_timetasks::create($data);
            if($res){
                $return = [
                    'status'=>200,
                    'msg' => '定时任务发布成功！'
                ];
            }else{
                $return = [
                    'status'=>500,
                    'msg' => '定时任务发布失败！'
                ];
            }
        }else{
            $data = [
                'name' => $request->name,
                'jsondata' => $request->jsondata,
                'server' => $request->serverx
            ];
            $res = rpa_immedtasks::create($data);
            if($res){
                $return = [
                    'status'=>200,
                    'msg' => '立即任务发布成功！'
                ];
            }else{
                $return = [
                    'status'=>500,
                    'msg' => '立即任务发布失败！'
                ];
            }
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($return,true),$request->getClientIp());

        return response()->json($return);
    }

    /**
     * dtu短信保存
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dtu_save_sms(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }
        //表单验证
        $validatedData = $request->validate([
            'phone' => 'required|numeric',
            'text' => 'required'
        ]);

        $data = [
            'phone' => $request->phone,
            'content' => $request->text
        ];
        $sms = RpaDtuSms::create($data);
        if($sms){
            $re = [
                'status' => 200,
                'msg' => "短信保存成功"
            ];
        }else{
            $re = [
                'status' => 500,
                'msg' => "短信保存失败"
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());


        return response()->json($re);
    }

    /**
     * dtu短信读取
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dtu_get_sms(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        $keywords = isset($request->keywords) ? $request->keywords : "";

        $sms = RpaDtuSms::where("content","like","%".$keywords.'%')->orderBy("created_at","desc")->first();

        if($sms){
            $return = [
                'status' => 200,
                'msg' => $sms
            ];
        }else{
            $return = [
                'status' => 500,
                'msg' => "未找到匹配的短信"
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($return,true),$request->getClientIp());

        return response()->json($return);
    }

    /**
     * 投资者密码接口
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function investor_password(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }
        //表单验证
        $validatedData = $request->validate([
            'filename' => 'required'
        ]);

        $file = $request->filename;
        // 打开csv文件
        $handle = fopen($file,"r");
        if(!$handle){
            $return = [
                'status' => 500,
                'msg' => "打开文件失败"
            ];

            //api日志
            $this->apiLog(__FUNCTION__,$request,json_encode($return,true),$request->getClientIp());

            return response()->json($return);
        }
        //解析csv文件
        $line = 0;
        while(!feof($handle)){
            $row = fgetcsv($handle);
            //跳过表头
            if($line == 0 || empty($row[2])){
                $line++;
                continue;
            }
            //处理数据
            foreach($row as $k => $v){
                $row[$k] = trim($v);
            }
            $csv_zjzh = iconv("gbk","utf-8",$row[2]);
            //crm获取交易编码
            $post_data = [
                'type' => 'common',
                'action' => 'getEveryBy',
                'param' => [
                    'table' => 'JYBM',
                    'by' => [
                        ['KHH','=',$csv_zjzh]
                    ],
                    'columns' => ['JYBM']
                ]
            ];
            $result = $this->getCrmData($post_data);

            if(isset($result[0]['JYBM']) && $result[0]['JYBM'] != ""){
                //有交易编码
                $has_jymb = 1;
            }else{
                //没有交易编码
                $has_jymb = 0;
            }
            $time = time();
            // 存入内部系统
            $sql = "insert into `oa_office_send` (`name`,`account`,`pwd`,`tel`,`has_jybm`,`fzjg`,`status`,`type`,`inputtime`,`content`) values ('{$row[1]}','{$row[2]}','{$row[3]}','{$row[4]}','{$has_jymb}','{$row[5]}',2,'{$row[6]}','{$time}','')";
            $res = DB::connection('oa')->insert($sql);
            $create = [
                'name' => $row[1],
                'account' => $row[2],
                'pwd' => $row[3],
                'tel' => $row[4],
                'has_jybm' => $has_jymb,
                'fzjg' => $row[5],
                'status' => 2,
                'type' => $row[6],
                'inputtime' => $time,
                'content' => ''
            ];
            rpa_customer_jkzx::create($create);
            if($res){
                $return = [
                    'status' => $row[2]."*1",
                    'msg' => "数据录入成功"
                ];
            }else{
                $return = [
                    'status' => $row[2]."*0",
                    'msg' => "数据录入失败"
                ];
            }
            echo json_encode($return);
        }
        fclose($handle);
    }

    /**
     * 获取客户信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_customer_info(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'type' => 'required|in:1,2,3,4,5',
            'date' => 'date_format:Y-m-d'
        ]);

        $date = isset($request->date) ? $request->date : date("Y-m-d");
        $post_data = [
            'type' => 'customer',
            'action' => 'getCustomer',
            'param' => [
                'type' => $request->type,
                'date' => $date
            ]
        ];
        $result = $this->getCrmData($post_data);
        $return = [
            'status' => 200,
            'msg' => $result
        ];

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($return,true),$request->getClientIp());

        return response()->json($return);
    }

    /**
     * 通过客户号获取客户信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_customer_by_khh(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'khh' => 'required|numeric',
        ]);

        $post_data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'KHXX',
                'by' => [
                    ['KHH','=',$request->khh]
                ]
            ]
        ];
        $result = $this->getCrmData($post_data);
        if($result){
            $return = [
                'status' => 200,
                'msg' => $result
            ];
        }else{
            $return = [
                'status' => 500,
                'msg' => "未找到数据！"
            ];
        }
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($return,true),$request->getClientIp());

        return response()->json($return);
    }

    /**
     * 获取客户可用资金
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_customer_kyzj(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'khh' => 'required|numeric',
        ]);

        $post_data = [
            'type' => 'customer',
            'action' => 'get_customer_kyzj',
            'param' => [
                'khh' => $request->khh
            ]
        ];
        $result = $this->getCrmData($post_data);
        if($result){
            $return = [
                'status' => 200,
                'msg' => $result
            ];
        }else{
            $return = [
                'status' => 500,
                'msg' => "未找到数据！"
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($return,true),$request->getClientIp());

        return response()->json($return);
    }

    /** 高管短信接口
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function gg_sms(Request $request){
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'type' => 'required'
        ]);
        // 没id代表查所有的数据
        if(!isset($request->ids)){
            $sql = "select * from THAQH_LCTZ";
        }else{
            if($request->type == 'delete'){
                $sql = "delete from THAQH_LCTZ where id in (".$request->ids." )";
            }else{
                $sql = "select * from THAQH_LCTZ where id in (".$request->ids.")";
            }
        }
        $post_data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'LCTZ',
                'by' => $sql
            ]
        ];
        $return = $this->getCrmData($post_data);
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($return,true),$request->getClientIp());

        return response()->json($return);
    }

    /** crm流程监控
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function flow(Request $request){
        set_time_limit(0);
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'date' => 'date_format:Y-m-d H:i:s',
        ]);

        $sql = "select TABLENAME,DESCRIBE,INSTID,STATE,INITIATOR, to_char(INIT_DATE,'yyyy-mm-dd hh24:mi:ss') INIT_DATE,SUBJECT,STEP_ID, to_char(LAST_DATE,'yyyy-mm-dd hh24:mi:ss') LAST_DATE from V_HA_LCZTCX where LAST_DATE>to_date('".$request->date."','yyyy-mm-dd hh24:mi:ss')";
        $post_data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'LCZTCX',
                'by' => $sql
            ]
        ];
        $return = $this->getCrmData($post_data);
        //api日志
        $this->apiLog(__FUNCTION__,$request,"",$request->getClientIp());

        return response()->json($return);
    }

    /** 官网手续费
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function gw_fee(Request $request){
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'type' => 'required|in:1,2'
        ]);

        if($request->type == 1){
            $sql = "SELECT A.FUTUFARE_TYPE,A.FUTU_EXCH_TYPE,A.FUTUCODE_TYPE,C.DICT_PROMPT,A.FUTU_CODE,A.FUTUFARE_SET_TYPE,
                    A.BALANCE_RATIO AS BALANCE_RATIO1,A.PERHAND_BALANCE AS PERHAND_BALANCE1,
                    A.TODAY_BALANCE_RATIO AS TODAY_BALANCE_RATIO1,A.TODAY_PERHAND_BALANCE AS TODAY_PERHAND_BALANCE1,
                    B.BALANCE_RATIO AS BALANCE_RATIO2,B.PERHAND_BALANCE AS PERHAND_BALANCE2,
                    B.TODAY_BALANCE_RATIO AS TODAY_BALANCE_RATIO2,B.TODAY_PERHAND_BALANCE AS TODAY_PERHAND_BALANCE2 
                    FROM hs_user.fumodelfare A , hs_user.fumodelfare B ,hs_user.sysdictionary C
                    WHERE ((A.FUTUFARE_TYPE=1 AND B.FUTUFARE_TYPE=2)) AND A.FUTUCODE_TYPE=B.FUTUCODE_TYPE  AND C.dict_entry='250026' AND A.FUTUCODE_TYPE=C.SUBENTRY
                    AND A.FUTU_CODE=B.FUTU_CODE AND A.MODEL_KIND=".$request->model_kind." AND B.MODEL_KIND=".$request->model_kind." AND A.FOPT_TYPE=B.FOPT_TYPE AND B.FOPT_TYPE<>'2'";
        }else{
            $sql = "SELECT A.FUTUFARE_TYPE AS FUTUFARE_TYPE,A.FUTU_EXCH_TYPE,A.FUTUCODE_TYPE,C.DICT_PROMPT,A.FUTU_CODE,
                    A.BALANCE_RATIO AS BALANCE_RATIO1,A.PERHAND_BALANCE AS PERHAND_BALANCE1,
                    A.TODAY_BALANCE_RATIO AS TODAY_BALANCE_RATIO1,A.TODAY_PERHAND_BALANCE AS TODAY_PERHAND_BALANCE1,
                    B.BALANCE_RATIO AS BALANCE_RATIO2,B.PERHAND_BALANCE AS PERHAND_BALANCE2,
                    B.TODAY_BALANCE_RATIO AS TODAY_BALANCE_RATIO2,B.TODAY_PERHAND_BALANCE AS TODAY_PERHAND_BALANCE2 
                    FROM hs_user.fuexchfare A , hs_user.fuexchfare B ,hs_user.sysdictionary C
                    WHERE ((A.FUTUFARE_TYPE=1 AND B.FUTUFARE_TYPE=2) OR (A.FUTUFARE_TYPE=4 AND B.FUTUFARE_TYPE=4))
                    AND A.FUTUCODE_TYPE=B.FUTUCODE_TYPE AND A.FUTU_CODE=B.FUTU_CODE AND A.FOPT_TYPE=B.FOPT_TYPE 
                    AND A.FOPT_TYPE<>'2' AND A.HEDGE_TYPE='!' AND B.HEDGE_TYPE='!'  AND C.dict_entry='250026' AND A.FUTUCODE_TYPE=C.SUBENTRY";
        }
        
        $post_data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'db' => 'uf20bak',
                'table' => 'TKHXX',
                'by' => $sql
            ]
        ];
        $return = $this->getCrmData($post_data);
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($return,true),$request->getClientIp());

        return response()->json($return);
    }

    /** 查询客户表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function monitor_kh(Request $request){
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        $date = isset($request->date) ? $request->date : date("Ymd",time());
        $sql = "select count(*) as count from tkhxx where KHRQ >= '".$date."' and KHRQ < '".date('Ymd',strtotime('+1day',strtotime($date)))."'";
        $post_data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'TKHXX',
                'by' => $sql
            ]
        ];
        $return = $this->getCrmData($post_data);
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($return,true),$request->getClientIp());

        return response()->json($return);
    }

    /**
     * 测试CRM数据库连接
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function crm_connection(Request $request){
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }
        $post_data = [
            'type' => 'common',
            'action' => 'index',
            'param' => [
                
            ]
        ];
        $res = $this->getCrmData($post_data);
        if($res == 'true'){
            $return = [
                'status' => 200,
                'msg' => "数据库连接成功"
            ];
        }else{
            $return = [
                'status' => 500,
                'msg' => $res
            ];
        }
        
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($return,true),$request->getClientIp());

        return response()->json($return);
    }

    /**
     * 仿真开户
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function simulation(Request $request){
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'name' => 'required',
            'sfz' => 'required',
            'phone' => 'required|numeric',
            'address' => 'required',
            'postcode' => 'required|numeric',
            'email' => 'required|email',
            'isCtp' => 'required|in:0,1',
        ]);

        //判断是否已经申请过了
        $acc = RpaSimulationAccount::where('sfz', $request->sfz)->first();
        if(!$acc){
            $data = [
                'name' => $request->name,
                'sfz' => $request->sfz,
                'phone' => $request->phone,
                'address' => $request->address,
                'postcode' => $request->postcode,
                'email' => $request->email,
                'isCtp' => $request->isCtp,
            ];
            $res = RpaSimulationAccount::create($data);
            if($res){
                //发布任务
                $this->rpa_task('FzkhGetData',$res->id);

                $return = [
                    'status' => 200,
                    'msg' => '申请成功'
                ];            
            }else{
                $return = [
                    'status' => 500,
                    'msg' => '申请失败'
                ];
            }
        }else{
            $return = [
                'status' => 500,
                'msg' => '该身份证已经注册过了'
            ];
        }
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($return,true),$request->getClientIp());

        return response()->json($return);
    }


    /**
     * 仿真开户短信发送
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function simulation_open(Request $request){
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'id' => 'required|numeric',
            'zjzh' => 'required'
        ]);

        $res = RpaSimulationAccount::where("id",$request->id)->update(['zjzh'=>$request->zjzh,'khdate'=>time()]);
        if($res){
            $kh = RpaSimulationAccount::find($request->id);
            $content = "尊敬的".$kh->name."：您好，您的仿真期权账号为".$request->zjzh."，初始密码为身份证后六位，可于下一个交易日参与交易。请到华安期货官网-软件下载-其他及模拟仿真栏目下载软件，推荐下载“期权仿真恒生-5.0”";
            $res = $this->yx_sms($kh->phone,$content);
            if($res['status'] == '0'){
                $re = [
                    'status' => 200,
                    'msg' => '数据更新成功，短信发送成功'
                ];
            }else{
                $re = [
                    'status' => 500,
                    'msg' => '数据更新成，短信发送失败，失败原因：'.$res['msg']
                ];
            }
        }else{
            $re = [
                'status' => 500,
                'msg' => '数据更新失败'
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
        
    }


    private function rpa_task($name,$id){
        $param = [
            'name' => $name,
            'id' => $id
        ];
        $data1 = [
            'name' => 'TaskDistribution_im',
            'jsondata' => json_encode($param,JSON_UNESCAPED_UNICODE),
            'server' => '主服务器1'
        ];
        $res1 = rpa_immedtasks::create($data1);
    }

    /**
     * 合约费用调整
     */
    public function contract_cost_change_remind(Request $request){
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }
        ini_set('max_execution_time', '0');
        $re = (new PublishController())->publish();
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());
        return response()->json($re);
    }
}
