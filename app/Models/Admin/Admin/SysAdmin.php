<?php

namespace App\Models\Admin\Admin;

use App\models\admin\base\SysMail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class SysAdmin extends Authenticatable 
{
    use Notifiable;
    use HasRoles;

    protected $guard_name = 'admin';
 
    /** 
     * The attributes that are mass assignable. 
     * 
     * @var array 
     */ 
    protected $fillable = [ 
        'name', 'email', 'password', 'desc', 'sex', 'phone', 'realName','roleLists','groupID'
    ]; 
 
    /** 
     * The attributes that should be hidden for arrays. 
     * 
     * @var array 
     */ 
    protected $hidden = [ 
        'password', 'remember_token', 
    ];

}
