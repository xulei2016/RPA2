<?php

namespace App\Observers;

use App\Models\Admin\Admin\SysAdmin;
use App\Models\Admin\Admin\SysAdminGroup;
use App\Models\Admin\Base\SysMessage;
use App\Models\Admin\Base\SysRole;
use App\Notifications\Message;
use Illuminate\Support\Facades\Notification;

class SysMessageObserver{
    public function created(SysMessage $sysMessage){
        $sysAdmin = [];
        if(4 == $sysMessage->mode){
            $sysAdmins = SysAdmin::where("type",1)->get();
            foreach($sysAdmins as $admin){
                $sysAdmin[] = $admin;
            }
        }else if(3 == $sysMessage->mode){
            $role_ids = explode(',',$sysMessage->user);
            $roles = SysRole::whereIn('id',$role_ids)->get();
            foreach($roles as $role){
                foreach($role->users->where("type",1) as $admin){
                    $sysAdmin[] = $admin;
                }
            }
        }else if(2 == $sysMessage->mode){
            $group_ids = explode(',',$sysMessage->user);
            $groups = SysAdminGroup::whereIn('id',$group_ids)->get();
            foreach($groups as $group){
                foreach($group->users->where("type",1) as $admin){
                    $sysAdmin[] = $admin;
                }
            }
        }else if(1 == $sysMessage->mode){
            $admin_ids = explode(',',$sysMessage->user);
            $sysAdmins = SysAdmin::whereIn('id',$admin_ids)->get();
            foreach($sysAdmins as $admin){
                $sysAdmin[] = $admin;
            }
        }
        foreach($sysAdmin as $admin){
            $admin->increment('notification_count');
        }
        Notification::send($sysAdmin,new Message($sysMessage));
    }
}