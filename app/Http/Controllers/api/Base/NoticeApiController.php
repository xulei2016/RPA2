<?php

namespace App\Http\Controllers\Api\Base;

use Illuminate\Http\Request;
use App\Mail\MdEmail;
use App\models\admin\base\SysMail;
use App\Models\Admin\Base\SysMessage;
use App\Models\Admin\Rpa\rpa_maintenance;
use App\Models\Admin\Rpa\rpa_taskcollections;
use App\Models\Admin\Rpa\rpa_uploademail;
use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Support\Facades\Mail;
use App\Models\Admin\Base\SysSmsTpl;
use App\Models\Admin\Rpa\rpa_filebinarys;
use GuzzleHttp\Client;

class NoticeApiController extends BaseApiController
{
    private $redrect = "";//跳转

    public function __construct()
    {
        $redirect = $this->isredirect();
        $this->redrect = $redirect;
    }

    /**
     * 短信发送接口
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sms(Request $request)
    {
        //跳转检测
        $res = $this->change($request->getRequestUri(),$request->all());
        if($res !== true){
            return response()->json($res);
        }
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'phone' => 'required',
            'msg' => 'required',
            'type' => 'in:zzy_sms,yx_sms'
        ]);

        if($request->type == "zzy_sms"){
            $data = $this->zzy_sms($request->phone,$request->msg);
        }elseif($request->type == "yx_sms"){
            $data = $this->yx_sms($request->phone,$request->msg);
        }

        return response()->json(['status'=>200,'data'=> $data]);
    }

    /**
     * 邮件发送接口
     * @param [string] project          标题
     * @param [string] editor           内容
     * @param [string] type             类型
     * @param [string] to               发送邮箱，多个用";"隔开
     * @return  [Integer] $status       状态码
     * @return  [String]   $msg         状态信息
     */
    public function mail(Request $request)
    {
        //跳转检测
        $res = $this->change($request->getRequestUri(),$request->all());
        if($res !== true){
            return response()->json($res);
        }
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'project' => 'required',
            'editor' => 'required',
            'type' => 'required|in:1,2,3',
            'to' => 'required',
        ]);

        $data = [
            'title' => $request->project,
            'content' => $request->editor,
            'tid' => $request->type
        ];
        $sysmail = SysMail::create($data);
        $to = explode(',',$request->to);
        Mail::to($to)->send(new MdEmail($sysmail));
        if($sysmail){
            $data = [
                'status' => 200,
                'msg' => "邮件发送成功！"
            ];
        }else{
            $data = [
                'status' => 500,
                'msg' => "邮件发送失败！"
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($data,true),$request->getClientIp());

        return response()->json($data);
    }

    /**
     * 任务反馈邮件发送接口
     * @param [string]     $id            rpa_uploademails表id
     * @return  [Integer]  $status       状态码
     * @return  [String]   $msg         状态信息
     */
    public function task_notice(Request $request)
    {
        //跳转检测
        $res = $this->change($request->getRequestUri(),$request->all());
        if($res !== true){
            return response()->json($res);
        }
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'id' => 'required|numeric'
        ]);

        $id = $request->id;
        $uploadmail = rpa_uploademail::find($id);
        if($uploadmail){
            $maintenance = rpa_maintenance::where([['name','=',$uploadmail['name']]])->first();
            if($maintenance){
                //任务设置不发送
                if($maintenance['notice_type'] != 0){
                    $admin = getAdmin($maintenance['notice_type'],$maintenance['noticeAccepter']);
                    // 获取通知的用户和id
                    $sysAdminIds = $admin['sysAdminIds'];
                    $sysAdmin = $admin['sysAdmin'];
                    //取出所有要发送的手机号和邮箱
                    $phones = [];
                    $emails = [];
                    foreach ($sysAdmin as $v){
                        $phones[] = $v->phone;
                        $emails[] = $v->email;
                    }

                    $data = [
                        'status' => 200,
                        'emails' => $emails,
                        'phones' => $phones
                    ];
                    //邮件内容为空不发送
                    if(empty($uploadmail['content'])){
                        rpa_uploademail::where('id',$id)->update(['state'=>'不发送']);
                        $mail = "邮件不发送";
                    }else{
                        //发邮件
                        $data1 = [
                            'title' => $maintenance['bewrite']."任务运行反馈",
                            'content' => $uploadmail['content'],
                            'tid' => 2
                        ];
                        $sysmail = SysMail::create($data1);
                        $sysmail->admins()->attach($sysAdminIds);
                        Mail::to($sysAdmin)->send(new MdEmail($sysmail));
                        if($sysmail){
                            $mail = "邮件发送成功";
                            rpa_uploademail::where('id',$id)->update(['state'=>'已发送']);
                        }else{
                            $mail = "邮件发送失败";
                            rpa_uploademail::where('id',$id)->update(['state'=>'发送失败']);
                        }
                    }
                    //短信内容为空不发送
                    if(empty($uploadmail['SMS'])){
                        $sms = "短信不发送";
                    }else{
                        $ph = implode(",", $phones);
                        $smsdata = $this->zzy_sms($ph,$uploadmail['SMS']);
                        $sms = $smsdata['msg'];
                    }
                    $data['mail'] = $mail;
                    $data['sms'] = $sms;
                }else{
                    rpa_uploademail::where('id',$id)->update(['state'=>'不发送']);
                    $data = [
                        'status' => 200,
                        'mail' => '邮件不发送',
                        'sms' => '短信不发送',
                        'phones' => '',
                        'emails' => ''
                    ];
                }
            }else{
                $data = [
                    'status' => 500,
                    'msg' => "任务名称错误！"
                ];
            }
        }else{
            $data = [
                'status' => 500,
                'msg' => "数据查询失败！"
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($data,true),$request->getClientIp());

        return response()->json($data);
    }

    public function test_notice(Request $request)
    {
        //表单验证
        $validatedData = $request->validate([
            'id' => 'required|numeric'
        ]);

        $id = $request->id;
        $uploadmail = rpa_uploademail::find($id);
        if($uploadmail){
            $maintenance = rpa_maintenance::where([['name','=',$uploadmail['name']]])->first();
            if($maintenance){
                //任务设置不发送
                if($maintenance['notice_type'] != 0){
                    $admin = getAdmin($maintenance['notice_type'],$maintenance['noticeAccepter']);
                    // 获取通知的用户和id
                    $sysAdminIds = $admin['sysAdminIds'];
                    $sysAdmin = $admin['sysAdmin'];
                    //取出所有要发送的手机号和邮箱
                    $phones = [];
                    $emails = [];
                    foreach ($sysAdmin as $v){
                        $phones[] = $v->phone;
                        $emails[] = $v->email;
                    }

                    $data = [
                        'status' => 200,
                        'emails' => $emails,
                        'phones' => $phones
                    ];
                    //邮件内容为空不发送
                    if(empty($uploadmail['content'])){
                        rpa_uploademail::where('id',$id)->update(['state'=>'不发送']);
                        $mail = "邮件不发送";
                    }else{
                        //发邮件
                        $data1 = [
                            'title' => $maintenance['bewrite']."任务运行反馈",
                            'content' => $uploadmail['content'],
                            'tid' => 2
                        ];
                        $sysmail = SysMail::create($data1);
                        $sysmail->admins()->attach($sysAdminIds);
                        Mail::to($sysAdmin)->send(new MdEmail($sysmail));
                        if($sysmail){
                            $mail = "邮件发送成功";
                            rpa_uploademail::where('id',$id)->update(['state'=>'已发送']);
                        }else{
                            $mail = "邮件发送失败";
                            rpa_uploademail::where('id',$id)->update(['state'=>'发送失败']);
                        }
                    }
                    //短信内容为空不发送
                    if(empty($uploadmail['SMS'])){
                        $sms = "短信不发送";
                    }else{
                        $ph = implode(",", $phones);
                        $smsdata = $this->zzy_sms($ph,$uploadmail['SMS']);
                        $sms = $smsdata['msg'];
                    }
                    $data['mail'] = $mail;
                    $data['sms'] = $sms;
                }else{
                    rpa_uploademail::where('id',$id)->update(['state'=>'不发送']);
                    $data = [
                        'status' => 200,
                        'mail' => '邮件不发送',
                        'sms' => '短信不发送',
                        'phones' => '',
                        'emails' => ''
                    ];
                }
            }else{
                $data = [
                    'status' => 500,
                    'msg' => "任务名称错误！"
                ];
            }
        }else{
            $data = [
                'status' => 500,
                'msg' => "数据查询失败！"
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($data,true),$request->getClientIp());

        return response()->json($data);
    }

    /**
     * 消息通知接口
     * @param   [Integer]  $id     任务回收表id
     * @return  [Integer] $status  状态码
     * @return  [String]   $msg    状态信息
     */
    public function message(Request $request)
    {
        //跳转检测
        $res = $this->change($request->getRequestUri(),$request->all());
        if($res !== true){
            return response()->json($res);
        }
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'id' => 'required|numeric'
        ]);

        $id = $request->id;
        $taskcollection = rpa_taskcollections::find($id);
        if($taskcollection){
            if($taskcollection['content']){
                $maintenance = rpa_maintenance::where([['name','=',$taskcollection['name']]])->first();
                if($maintenance){
                    $content = ($taskcollection['state'] == '成功') ? "<p>RPA服务-{$maintenance['name']}: {$taskcollection['content']}</p>" : '<p>RPA服务-'.$maintenance['name'].'-运行<b class="text-danger">失败</b>了！！！<br/>'.$taskcollection['updatetime']."</p>" ;
                    $message = [
                        //消息内容 || 执行结果
                        'title' => 'RPA服务-'.$maintenance['bewrite'].'任务运行--'.$taskcollection['state'],
                        'content' => $content,
                        'type' => 2,
                        'mode' => $maintenance['notice_type'],
                        'user' => $maintenance['noticeAccepter'],
                        'add_time' => date('Y-m-d H:i:s',time())
                    ];
                    $res = SysMessage::create($message);
                    if($res){
                        $data = [
                            'status' => 200,
                            'msg' => "通知发送成功！"
                        ];
                    }else{
                        $data = [
                            'status' => 500,
                            'msg' => "通知发送失败！"
                        ];
                    }
                }else{
                    $data = [
                        'status' => 500,
                        'msg' => "任务名称错误！"
                    ];
                }
            }else{
                $data = [
                    'status' => 200,
                    'msg' => "无需通知" 
                ];
            }
        }else{
            $data = [
                'status' => 500,
                'msg' => "回收任务查询失败！"
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($data,true),$request->getClientIp());

        return response()->json($data);
    }

    /**
     * 获取短信模板
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sms_tpl(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'type' => 'required'
        ]);

        $res = SysSmsTpl::where("type",$request->type)->get();
        if($res){
            $re = [
                'status' => 200,
                'msg' => $res
            ];
        }else{
            $re = [
                'status' => 500,
                'msg' => '未找到数据'
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
    }

    /**
     * 模板短信发送
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function tpl_send(Request $request)
    {
        //跳转检测
        $res = $this->change($request->getRequestUri(),$request->all());
        if($res !== true){
            return response()->json($res);
        }
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'name' => 'required',
            'phone' => 'required|numeric',
            'ids' => 'required',
        ]);

        $tpls = SysSmsTpl::whereIn("id",explode(",",$request->ids))->get();
        if($tpls){
            //发短信
            $error = 1;//记录发送失败短信
            foreach($tpls as $v){
                if($v->title != "不发短信" && !empty($v->content)) {
                    $content = "尊敬的" . $request->name . "您好：" . $v->content . "，我司会尽快为您办理开户事宜，感谢您的配合！";

                    $data = $this->yx_sms($request->phone,$content);

                    if ($data['status'] != 0) {
                        $error = 0;
                    }
                }
            }
            if($error){
                $re = [
                    'status' => 200,
                    'msg' => '短信发送成功！'
                ];
            }else{
                $re = [
                    'status' => 500,
                    'msg' => '短信发送失败！'
                ];
            }
        }else{
            $re = [
                'status' => 500,
                'msg' => '未找到该短信模板'
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
    }

    /** 验证码识别
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function code_distinguish(Request $request)
    {
        //跳转检测
        $res = $this->change($request->getRequestUri(),$request->all());
        if($res !== true){
            return response()->json($res);
        }
//        ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'id' => 'required',
        ]);
        //根据id获取图片二进制

        $file = rpa_filebinarys::find($request->id);
        $data = $this->base64ToImage($file->filebinary);
        $path = "d:/uploadFile/".$data['filename'];
        $type = $file->codetype;

        $config = $this->get_config(['code_distinguish']);
        $func = $config['code_distinguish'];
        if($func == '尖叫'){
            $return = $this->jianjiao($file->filebinary,'cn');
        }elseif($func == '云打码'){
            $return = $this->yundama($path,$type);
        }else{
            $func = config('code.default');
            $return = $this->$func($path,$type);
        }
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($return,true),$request->getClientIp());

        return response()->json($return);
    }

    /** 尖叫识别验证码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function jianjiao($file_path,$type)
    {
        $data = [
            'v_pic' => $file_path,
            'v_type' => $type
        ];
        $guzzle = new Client(['verify'=>false]);
        $response = $guzzle->post(config('code.jianjiao.url.mult'), [
            'headers' => [
                'appcode' => config('code.jianjiao.APPCODE'),
                'appKey' => config('code.jianjiao.APPKEY'),
                'appSecret' => config('code.jianjiao.APPSECRET'),
                'Content-Type' => "application/x-www-form-urlencoded; charset=UTF-8",
            ],
            'form_params' => $data,
        ]);
        $body = $response->getBody();
        $body = json_decode((string)$body,true);
        if($body['errCode'] == 0){
            $return = [
                'status' => 200,
                'msg' => $body['v_code']
            ];
        }else{
            $return = [
                'status' => 500,
                'msg' => $body['msg']
            ];
        }
        return $return;
    }

    /** 云打码识别验证码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function yundama($file_path,$type)
    {
        $url = config('code.yundama.url.mult');
        $form_params = [
            'method' => 'upload',
            'username'=> config('code.yundama.USERNAME'),
            'password'=> config('code.yundama.PASSWORD'),
            'appid'=> config('code.yundama.APPID'),
            'appkey'=> config('code.yundama.APPKEY'),
            'codetype'=> $type,
            'timeout'=> 60,
            'file'=> new \CURLFile(realpath($file_path))
        ];
        $body = $this->http($url,"POST",$form_params);
        $data = json_decode($body);
        if(is_object($data) && property_exists($data, 'cid')){
            $cid = $data->cid;
            for($i=1;$i>0;){
                $posts = array(
                    'method'=>'result',
                    'username'=> config('code.yundama.USERNAME'),
                    'password'=> config('code.yundama.PASSWORD'),
                    'appid'=> config('code.yundama.APPID'),
                    'appkey'=> config('code.yundama.APPKEY'),
                    'cid'=>$cid);
                $data = $this->http($url, 'POST',$posts);
                $data = json_decode($data);

                if(is_object($data) && property_exists($data, 'text') && $data->text){
                    unlink($file_path);
                    $return['status'] = 200;
                    $return['msg'] = $data->text;
                    break;
                }
            }
        }
        return $return;
    }

    /************************************工具方法***************************************/
    /**
     * 接口是否需要转移
     * @return string
     */
    private function isredirect()
    {
        $redrect = "";
        $server = $this->get_config();
        $config = $this->get_config(['H1_inner','H2_inner','rpa_clock_interval']);
        //判断当前服务器是否是从服务器
        if($server == "S1_inner" || $server == "S2_inner"){
            //判断从服务器是否正常工作
            $cards = $this->getCard();
            $sign = 0;
            foreach($cards['info'] as $card){
                // 主一
                if($card->host == 'H1_inner' && (time() - $card->created_at < $config['rpa_clock_interval']*2)){
                    $sign = 1;
                    $redrect = "rpa.haqh.com";
                    return $redrect;
                }
                //主二
                if($card->host == 'H2_inner' && (time() - $card->created_at < $config['rpa_clock_interval']*2)){
                    $sign = 1;
                    $redrect = "rpa.slave.haqh.com";
                    return $redrect;
                }
            }
            if($sign == 0){
                $redrect = -1;
            }
        }
        return $redrect;
    }

    /**
     * 接口转移
     * @param $url
     * @param $data
     * @return \Psr\Http\Message\StreamInterface|string
     */
    private function change($url,$data){
        if($this->redrect == -1){
            $data = ['status' => 500, 'msg' => '没有可用服务器'];
            return $data;
        }
        if($this->redrect != ""){
            //主机名
            $host = "https://".$this->redrect.":8088";
            //获取token
            $token = $this->access_token($host);
            $guzzle = new Client(['verify'=>false]);
            $response = $guzzle->post($host.$url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => $token
                ],
                'form_params' => $data,
            ]);
            $body = $response->getBody();
            $body = json_decode((string)$body,true);

            return $body;
        }
        return true;
    }
}
