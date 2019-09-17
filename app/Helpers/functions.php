<?php
/**
 * 公共方法
 */

// 获取发送邮件的用户和id
function getAdmin($mode,$user)
{
    //$user 传过来的可能是数组也可能是字符串
    if(!is_array($user)){
        $user = explode(",",$user);
    }
    $sysAdminIds = [];
    $sysAdmin = [];
    if(4 == $mode){
        $sysAdmins = \App\Models\Admin\Admin\SysAdmin::where("type",1)->get();
        foreach($sysAdmins as $admin){
            $sysAdminIds[] = $admin->id;
            $sysAdmin[] = $admin;
        }
    }else if(3 == $mode){
        $role_ids = $user;
        $roles = \App\Models\Admin\Base\SysRole::whereIn('id',$role_ids)->get();
        foreach($roles as $role){
            foreach($role->users->where("type",1) as $admin){
                $sysAdminIds[] = $admin->id;
                $sysAdmin[] = $admin;
            }
        }
    }else if(2 == $mode){
        $group_ids = $user;
        $groups = \App\Models\Admin\Admin\SysAdminGroup::whereIn('id',$group_ids)->get();
        foreach($groups as $group){
            foreach($group->users->where("type",1) as $admin){
                $sysAdminIds[] = $admin->id;
                $sysAdmin[] = $admin;
            }
        }
    }else if(1 == $mode){
        $admin_ids = $user;
        $sysAdmins = \App\Models\Admin\Admin\SysAdmin::whereIn('id',$admin_ids)->get();
        foreach($sysAdmins as $admin){
            $sysAdminIds[] = $admin->id;
            $sysAdmin[] = $admin;
        }
    }
    return [
        'sysAdminIds'=>$sysAdminIds,
        'sysAdmin' => $sysAdmin
    ];
}

/**
 * 中正云短信接口
 * @param   [String]  $phone    手机号 多个手机号用“,”分割
 * @param   [String]  $msg      发送内容
 * @return  [Integer] $code     状态码
 * @return  [String]  $data     返回信息
 */
function zzy_sms($phone,$msg)
{
    $msg = iconv("utf-8","gb2312",$msg);

    $zzy = config('sms.ZZY');
    $url = $zzy['url']['mult'];
    $statuses = $zzy['status'];

    $form_params = [
        'id' => $zzy['account'],
        'pwd' => $zzy['password'],
        'to' => $phone,
        'content' => $msg,
    ];

    $guzzle = new GuzzleHttp\Client();
    $response = $guzzle->post($url, [
        'form_params' => $form_params
    ]);
    $body = $response->getBody();

    $status = explode('/',(string)$body)[0];

    $data = [
        'status' => $status,
        'msg' => $statuses[$status]
    ];

    smsLog('中正云', $phone, iconv("gb2312","utf-8",$msg), $status);

    return $data;
}

/**
 * 优信短信接口
 * @param   [String]  $phone    手机号
 * @param   [String]  $msg      发送内容
 * @return  [Integer] $code     状态码
 * @return  [String]  $data     返回信息
 */
function yx_sms($phone,$msg)
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

    $guzzle = new GuzzleHttp\Client();
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
    smsLog('优信', $phone, iconv("gb2312","utf-8",$msg), $body);

    return $data;
}

/**
 * 短信发送日志
 * @Description 短信日志
 * @param [string] $type
 * @param [string] $phone
 * @param [string] $content
 * @param [string] $status
 * @return void bool
 */
function smsLog($type, $phone, $content, $status){
    $sms = [
        'type' => $type,
        'api' => 'sms',
        'phone' => $phone,
        'content' => $content,
        'return' => $status,
    ];
    return App\Models\Admin\Base\SysSmsLog::create($sms);
}

/**
 * 生成密码
 */
function createPassword($name){
    return \Illuminate\Support\Facades\Crypt::encrypt('H@qh9772_'.$name);
}

?>