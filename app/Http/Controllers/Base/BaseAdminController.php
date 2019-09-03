<?php

namespace App\Http\Controllers\base;

use Illuminate\Http\Request;
use App\Models\Admin\Base\SysMenu;
use App\Models\Admin\Admin\SysAdmin;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Base\SysConfig;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Base\BaseController;
use GuzzleHttp\Client;
use App\Models\Admin\Base\SysSmsLog;

/**
 * 基础控制器
 * @author hsu lay
 * @since 2018/2
 */
class BaseAdminController extends BaseController
{
    public function __CONSTRUCT(){
        $this->get_sysConfigs();
    }

    public function get_sysConfigs()
    {
        if (!Cache::has("sysConfigs")) {
            $sysConfigs = SysConfig::get();
            if($sysConfigs){
                Cache::add("sysConfigs",$sysConfigs,3600);
            }
        }
    }

    /**
     * 优信短信接口
     * @param   [String]  $phone    手机号
     * @param   [String]  $msg      发送内容
     * @return  [Integer] $code     状态码
     * @return  [String]  $data     返回信息
     */
    public function yx_sms($phone,$msg)
    {
        $msg = iconv("utf-8","gb2312",$msg);

        $yx = config('sms.YX');
        $url = $yx['url']['mult'];
        $statuses = $yx['status'];

        $form_params = [
            'CorpID' => $yx['account'],
            'Pwd' => $yx['password'],
            "Mobile" => $phone,
            "Content" => $msg,
            "Cell" => '',
            "SendTime" => ''
        ];

        $guzzle = new Client();
        $response = $guzzle->post($url, [
            'form_params' => $form_params
        ]);
        $body = $response->getBody();
        $body = (string)$body;

        $data = [
            'status' => $body,
            'msg' => $statuses[$body]
        ];

        //短信日志
        $sms = [
            'type' => '优信',
            'api' => 'sms',
            'phone' => $phone,
            'content' => iconv("gb2312","utf-8",$msg),
            'return' => $body,
        ];
        SysSmsLog::create($sms);

        return $data;
    }

    /**
     * 删除session
     * flush 清除session并不管什么session键前缀，而是从session系统中移除所有数据，所以在使用这个方法时如果其他应用与本应用有共享session时需要格外注意。
     */
    public function del_cache($name = null){
        if($name){
            session()->forget($name);
        }else{
            session()->flush();
        }
        return true;
    }

    //获取数组数据
    public function get_one($data){
        foreach($data as $param){
            yield $param;
        }
    }
        
    /**
     * session管理员信息
     * @return bool 
     */
    protected function authCacheInfo ($type = TRUE){
        //更新登录信息
        $admin_info = auth()->guard('admin')->user();
        if(!$admin_info){
            header('Location: /admin/logout');exit;
        }
        $id = (int) $admin_info->id;
        $admin = new \App\Models\Admin\Admin\SysAdmin();
        $info['lastIp'] = $this->getRealIp();
        $info['lastTime'] = $this->getTime();
        $info['isMobile'] = $this->isMobile()['isMobile'] ? 1 : 0 ;
        $info['lastAgent'] = $_SERVER['HTTP_USER_AGENT'];
        $info['lastAbbAgent'] = $this->isMobile()['userAgent'];
        // if($type)
            // $admin::where('id', $id)->update($info);

        //快捷获取管理员信息可从此处添加 $admin_info->***
        $info['id'] = $id;
        $info['roleLists'] = $admin_info->roleLists;
        $info['headImg'] = $admin_info->head_img;
        $info['name'] = $admin_info->name;
        $info['phone'] = $admin_info->phone;
        $info['realName'] = $admin_info->realName;
        $info['email'] = $admin_info->email;
        $info['theme'] = $admin_info->theme ? $admin_info->theme : 'lightseagreen' ;
        $info['lastTime'] = $admin_info->lastTime;
        $info['lastIp'] = $admin_info->lastIp;
        // $info['isMobile'] = $admin_info->isMobile;
        $info['lastAgent'] = $admin_info->lastAgent;
        session(['sys_admin' => $info]);
        return true;
    }
    
    //------------------日志管理-----------------

     /**
     * log
     * @param string $controller 控制器
     * @param string $action 动作
     * @param $request request
	 * @param string $desc 描述
     */
    public function log($controller, $action, $request, $desc) {
        $user_id = 0;
        if(auth()->guard('admin')->check()) {
            $user_id = (int) auth()->guard('admin')->user()->id;
            $account = auth()->guard('admin')->user()->name;
        }else{
            $account = $request->name;
        }
        $request->session()->has('sys_admin') ? session('sys_admin') : $this->authCacheInfo(false) ;
        $admin = session('sys_admin');

        $log = new \App\Models\Admin\Base\SysLog();
        $log->setAttribute('ip', $request->ip());
        $log->setAttribute('controller', strrchr($controller, '\\'));
        $log->setAttribute('action', $action);
        $log->setAttribute('simple_desc', $desc);
        $log->setAttribute('user_id', $user_id);
        $log->setAttribute('account', $account);
        $log->setAttribute('path', $request->path());
        $log->setAttribute('method', $request->method());
        $log->setAttribute('data', json_encode($request->all(), JSON_UNESCAPED_UNICODE));
        $log->setAttribute('agent', $admin['lastAbbAgent']);
        $log->save();
    }
}
