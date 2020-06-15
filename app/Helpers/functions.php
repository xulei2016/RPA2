<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
            $users = \App\Models\Admin\Admin\SysAdmin::role($role->name)->get();
            // foreach($role->users->where("type",1) as $admin){
            foreach($users as $admin){   
                if(1 === $admin->type){
                    $sysAdminIds[] = $admin->id;
                    $sysAdmin[] = $admin;
                }
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
 * 生成密码
 */
function createPassword($name, $flag = false){
    $a = 'H@qh9772_'.$name;
    if($flag) {
        return $a;
    } else {
        return bcrypt('H@qh9772_'.$name);
    }
   
}

/**
 * 显示配置名
 * @param $str
 * @return string
 */
function showConfigName($str){
    $str = trim($str);
    switch ($str) {
        case 'rpa':
            $result = 'RPA设置';
            break;
        case 'sys':
            $result = '系统设置';
            break;
        case 'common':
            $result = '通用配置';
            break;
        case 'extra':
            $result = '其它配置';
            break;
        case 'sms':
            $result = '短信配置';
            break;
        default:
            $result = '未知';
    }
    return $result;
}

/**
 * 小数点转百分数
 * @param $number
 * @return int|string
 */
function transNumber($number){
    if($number == 0) return 0;
    $number = round($number*100, 2);
    return $number."%";
}

/**
 * 图片转base64
 * @param $url
 * @param string $disk
 * @return string
 */
function image2base64($url, $disk = 'local'){
    $result = \Illuminate\Support\Facades\Storage::disk($disk)->get($url);
    return  "data:image/png;base64,".base64_encode($result);
}

/**
 * 解密
 * @param $str
 * @return string
 */
function customDecode($str){
    return \Illuminate\Support\Facades\Crypt::decrypt($str);
}

/**
 * 加密
 * @param $str
 * @return string
 */
function customEncode($str){
    return \Illuminate\Support\Facades\Crypt::encrypt($str);
}

/**
 * 身份证校验函数
 * @param $id_card
 * @return bool
 */
function validation_filter_id_card($id_card){
    if(strlen($id_card)==18){
        return idcard_checksum18($id_card);
    }elseif((strlen($id_card)==15)){
        $id_card=idcard_15to18($id_card);
        return idcard_checksum18($id_card);
    }else{
        return false;
    }
}

/**
 * @param $idcard_base
 * @return bool|mixed
 */
function idcard_verify_number($idcard_base){
    if(strlen($idcard_base)!=17){
        return false;
    }
    //加权因子
    $factor=array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2);
    //校验码对应值
    $verify_number_list=array('1','0','X','9','8','7','6','5','4','3','2');
    $checksum=0;
    for($i=0;$i<strlen($idcard_base);$i++){
        $checksum += substr($idcard_base,$i,1) * $factor[$i];
    }
    $mod=$checksum % 11;
    $verify_number=$verify_number_list[$mod];
    return $verify_number;
}
// 将15位身份证升级到18位
function idcard_15to18($idcard){
    if(strlen($idcard)!=15){
        return false;
    }else{
        // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
        if(array_search(substr($idcard,12,3),array('996','997','998','999')) !== false){
            $idcard=substr($idcard,0,6).'18'.substr($idcard,6,9);
        }else{
            $idcard=substr($idcard,0,6).'19'.substr($idcard,6,9);
        }
    }
    $idcard=$idcard.idcard_verify_number($idcard);
    return $idcard;
}
// 18位身份证校验码有效性检查
function idcard_checksum18($idcard){
    if(strlen($idcard)!=18){
        return false;
    }
    $idcard_base=substr($idcard,0,17);
    if(idcard_verify_number($idcard_base)!=strtoupper(substr($idcard,17,1))){
        return false;
    }else{
        return true;
    }
}

/**
 * 构建一个url路径
 * @param $url
 * @return string
 */
function buildImageUrl($url){
    return "/credit/showImg?url=".$url;
}


/**
 * @param $email
 * @return int
 */
function checkEmail($email)
{
    // Create the syntactical validation regular expression
    $regexp = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/";

    // Presume that the email is invalid
    $valid = 0;

    // Validate the syntax
    if (preg_match($regexp, $email)) {
        list($username, $domaintld) = preg_split("/@/", $email);
        // Validate the domain
        if (getmxrr($domaintld, $mxrecords))
            $valid = 1;
    } else {
        $valid = 0;
    }

    return $valid;
}

/**
 * @return array
 */
function getSMSConfig()
{
    if (!Cache::has("sysSMSConfig")) {

        $sysSMSConfig = DB::table('sys_configs')->where('item_group', 'sms')->get(['item_key', 'item_value', 'type']);
        $gateways = DB::table('sys_sms_settings')->where('status', 1)->get(['unique_name','setting']);

        $config = [];
        foreach($sysSMSConfig as $k => $SMSConfig){
            $config[$SMSConfig->item_key] = 'json' === $SMSConfig->type ? json_decode($SMSConfig->item_value, true): $SMSConfig->item_value ;
        }
        foreach($gateways as $gateway){
            $config['gateways'][$gateway->unique_name] = json_decode($gateway->setting, true);
        }

        if($config){
            Cache::forget('sysSMSConfig');
            Cache::add("sysSMSConfig",$config,3600);
        }
    }else{
        $config = Cache::get("sysSMSConfig");
    }
    return $config;
}