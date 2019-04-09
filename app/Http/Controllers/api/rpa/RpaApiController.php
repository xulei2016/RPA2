<?php

namespace App\Http\Controllers\api\rpa;

use App\Http\Controllers\api\BaseApiController;
use Illuminate\Http\Request;
use App\Models\Admin\Base\SysConfig;
use App\Models\Admin\Rpa\rpa_immedtasks;
use App\Models\Admin\Rpa\rpa_timetasks;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;
use App\Models\Admin\Api\RpaDtuSms;

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
            'CARD_IM' => 'required|integer',
            'CARD_TM' => 'required|integer',
            'CARD_EX' => 'required|integer',
            'CARD_RT' => 'required|integer',
            'CPU_sum' => 'required',
            'Memory_mem' => 'required',
            'Disks_mem' => 'required',
            'Process_p' => 'required|integer',
            'Process_e' => 'required|integer',
        ]);

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
            return response()->json(['status'=>500,'msg'=> "非rpa服务器无法调用该接口！"]);
        }
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
        $body = $response->getBody();

        $body = json_decode((string)$body,true);

        $re = [
            'status'=>$body['code'],
            'msg'=> $body['info']
        ];
        //api日志
        $this->apiLog(__FUNCTION__,$request,response()->json($re),$request->getClientIp());

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
        $this->apiLog(__FUNCTION__,$request,response()->json($re),$request->getClientIp());

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
            'jsondata' => 'required'
        ]);

        //time存在代表定时任务，不存在代表立即任务
        if(isset($request->time)){
            $data = [
                'time' => $request->time,
                'name' => $request->name,
                'jsondata' => $request->jsondata
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
                'jsondata' => $request->jsondata
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
        $this->apiLog(__FUNCTION__,$request,response()->json($return),$request->getClientIp());

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
            'phone' => 'required|integer',
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
        $this->apiLog(__FUNCTION__,$request,response()->json($re),$request->getClientIp());


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
        $this->apiLog(__FUNCTION__,$request,response()->json($return),$request->getClientIp());

        return response()->json($return);
    }
}
