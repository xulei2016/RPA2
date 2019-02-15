<?php

namespace App\Observers;

use App\Models\Admin\Base\SysMessage;
use App\Notifications\Message;
use Illuminate\Support\Facades\Notification;

class SysMessageObserver{
    public function created(SysMessage $sysMessage){

        $admin = getAdmin($sysMessage->mode,$sysMessage->user);
        $sysAdmin = $admin['sysAdmin'];
        foreach($sysAdmin as $admin){
            $admin->increment('notification_count');
        }
        Notification::send($sysAdmin,new Message($sysMessage));
    }
}