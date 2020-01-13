<?php

namespace App\Models\Admin\Admin;

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

    //'name', 'email', 'password', 'desc', 'sex', 'type','phone', 'realName','roleLists','groupID', 'dep_id', 'job_id'
    protected $guarded = [];
    /** 
     * The attributes that should be hidden for arrays. 
     * 
     * @var array 
     */ 
    protected $hidden = [ 
        'password', 'remember_token', 
    ];

    /**
     * dept
     */
    public function dept(){
        return $this->belongsTo('App\Models\Admin\Base\Organization\SysDept','dept_id');
    }

    /**
     * 岗位
     */
    public function relation(){
        return $this->hasManyThrough(
            'App\Models\Admin\Base\Organization\SysDeptPost',
            'App\Models\Admin\Base\Organization\SysDeptRelation', 
            'admin_id',
            'id',
            'id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function alerts(){
        return $this->hasMany('App\Models\Admin\Admin\SysAdminAlert', 'user_id');
    }

}
