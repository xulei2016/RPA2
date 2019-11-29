<?php

namespace App\Models\Admin\Base\Organization;

use Illuminate\Database\Eloquent\Model;

/**
 * SysDeptRelation class
 *
 * @Description
 */
class SysDeptRelation extends Model
{
    protected $guarded = [];

    /**
     * dept
     */
    public function dept(){
        $this->belongsTo('App\Models\Admin\Base\Organization\SysDept', 'dept_id');
    }

    /**
     * admin
     */
    public function admin(){
        $this->hasMany('App\Models\Admin\Admin\SysAdmin', 'admin_id');
    }

    /**
     * admin
     */
    public function post(){
        $this->hasMany('App\Models\Admin\Base\Organization\SysDeptPost', 'post_id');
    }
}
