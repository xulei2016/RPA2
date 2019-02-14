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
?>