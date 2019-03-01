<?php

namespace App\Http\Controllers\api;

use App\Mail\MdEmail;
use App\Models\Admin\Base\SysApiIp;
use App\Models\Admin\Base\SysApiLog;
use App\models\admin\base\SysMail;
use App\Models\Admin\Base\SysMessage;
use App\Models\Admin\Base\SysSmsLog;
use App\Models\Admin\Rpa\rpa_maintenance;
use App\Models\Admin\Rpa\rpa_taskcollections;
use App\Models\Admin\Rpa\rpa_uploademail;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class SysApiController extends Controller
{
    public function test()
    {
        //写日志
        $sms = [
            'api' => '中\正\云',
            'phone' => 1011111,
            'content' => 'CES',
            'return' => 00,
        ];
        SysSmsLog::create($sms);
    }
    /**
     * 中正云短信接口
     * @param   [String]  $phone    手机号 多个手机号用“,”分割
     * @param   [String]  $msg      发送内容
     * @return  [Integer] $code     状态码
     * @return  [String]  $data     返回信息
     */
    public function zzy_sms(Request $request)
    {
        //ip检测
        $res = $this->check_ip("zzy_sms",$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }
        $phone = $request->phone;
        $msg = iconv("utf-8","gb2312",$request->msg);

        $guzzle = new Client();
        $response = $guzzle->post('http://service.winic.org:8009/sys_port/gateway/index.asp',[
           'form_params' => [
               'id' => 'haqh',
               'pwd' => 'haqh9772rjgcb',
               'to' => $phone,
               'content' => $msg,
           ],
        ]);
        $body = $response->getBody();

        //状态码对照表
        $statuses = [
            '000' => '短信提交成功',
            '-01' => '账号余额不足',
            '-02' => '开通接口授权',
            '-03' => '账号密码错误',
            '-04' => '参数个数不对或者参数类型错误',
            '-110' => 'IP被限制	联系技术支持',
            '-12' => '其他错误'
        ];

        $status = explode('/',(string)$body)[0];

        $data = [
            'status' => $status,
            'msg' => $statuses[$status]
        ];
        //写日志
        $sms = [
            'api' => '中正云',
            'phone' => $phone,
            'content' => $request->msg,
            'return' => $status,
        ];
        SysSmsLog::create($sms);

        $log = [
            'api' => __FUNCTION__,
            'param' => $request,
            'return' => response()->json(['code'=>$response->getStatusCode(),'data'=> $data]),
            'ip' => $request->getClientIp()
        ];
        SysApiLog::create($log);

        return response()->json(['code'=>$response->getStatusCode(),'data'=> $data]);
    }

    /**
     * 优信短信接口
     * @param   [String]  $phone    手机号
     * @param   [String]  $msg      发送内容
     * @return  [Integer] $code     状态码
     * @return  [String]  $data     返回信息
     */
    public function yx_sms(Request $request)
    {
        //ip检测
        $res = $this->check_ip("yx_sms",$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }
        $phone = $request->phone;
        $msg = iconv("utf-8","gb2312",$request->msg);

        $guzzle = new Client();
        $response = $guzzle->post('http://www.106551.com/ws/Send.aspx',[
            'form_params' => [
                "CorpID" => "YX03941",
                "Pwd" => "123",
                "Mobile" => $phone,
                "Content" => $msg,
                "Cell" => '',
                "SendTime" => ''
            ],
        ]);
        $body = $response->getBody();
        $body = (string)$body;
        //状态码对照表
        $statuses = [
            '0' => '提交成功',
            '–1' => '账号未注册',
            '–2' => '其他错误',
            '–3' => '帐号或密码错误',
            '–5' => '余额不足，请先充值',
            '–6' => '定时发送时间不是有效的时间格式',
            '–8' => '发送内容需在1到500字之间',
            '-9' => '发送号码为空',
            '-10' => '定时时间不能小于当前系统时间',
            '-100' => '限制此IP访问',
            '-101' => '调用接口速度太快',
        ];
        $data = [
            'status' => $body,
            'msg' => $statuses[$body]
        ];
        //写日志
        $sms = [
            'api' => '优信',
            'phone' => $phone,
            'content' => $request->msg,
            'return' => $body,
        ];
        SysSmsLog::create($sms);

        $log = [
            'api' => __FUNCTION__,
            'param' => $request,
            'return' => response()->json(['code'=>$response->getStatusCode(),'data'=> $data]),
            'ip' => $request->getClientIp()
        ];
        SysApiLog::create($log);

        return response()->json(['code'=>$response->getStatusCode(),'msg'=> $data]);
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
        //ip检测
        $res = $this->check_ip("mail",$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }
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
        //ip检测
        $res = $this->check_ip("task_notice",$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }
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
                        $guzzle = new Client();
                        $response = $guzzle->post('http://service.winic.org:8009/sys_port/gateway/index.asp',[
                            'form_params' => [
                                'id' => 'haqh',
                                'pwd' => 'haqh9772rjgcb',
                                'to' => implode(",",$phones),
                                'content' => iconv("utf-8","gb2312",$uploadmail['SMS']),
                            ],
                        ]);
                        $body = $response->getBody();
                        $status = explode('/',(string)$body)[0];
                        if($status == '000'){
                            $sms = "短信发送成功";
                        }else{
                            $sms = "短信发送失败";
                        }
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
        //ip检测
        $res = $this->check_ip("message",$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }
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
            }
        }else{
            $data = [
                'status' => 500,
                'msg' => "回收任务查询失败！"
            ];
        }
        return response()->json($data);
    }

    /****************************工具方法******************************************/
    public function check_ip($api,$ip)
    {
        //获取黑白名单，先取缓存
        if (!Cache::has($api)) {
            $sysapiip = SysApiIp::where([['api','=',$api],['state','=',1]])->first();
            if(!$sysapiip){
                $data = [
                    'status' => 403,
                    'msg' => "接口未注册,或被禁用"
                ];
                return $data;
            }
            Cache::add($api,$sysapiip,3600);
        }else{
            $sysapiip = Cache::get($api);
        }
        //判断
        $black_list = array_keys($sysapiip->black_list ? json_decode($sysapiip->black_list,true) : []);
        $white_list = array_keys($sysapiip->white_list ? json_decode($sysapiip->white_list,true) : []);
        //有白名单存在，请求ip必须在白名单中
        if($white_list && !in_array($ip,$white_list)){
            $data = [
                'status' => 450,
                'msg' => "ip限制访问"
            ];
            return $data;
        }
        //有黑名单存在，请求ip必须不在黑名单中
        if($black_list && in_array($ip,$black_list)){
            $data = [
                'status' => 450,
                'msg' => "ip限制访问"
            ];
            return $data;
        }
        return true;
    }
}
