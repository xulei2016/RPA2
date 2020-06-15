<?php

namespace App\Models\Admin\Base;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class ApiUser extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;

    protected $table = "sys_api_users";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dept_id', 'name', 'email', 'password',
    ];

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

}
