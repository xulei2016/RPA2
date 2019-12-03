<?php

namespace App\Models\Admin\Base\Organization;

use Illuminate\Database\Eloquent\Model;

/**
 * SysDeptPostRelation class
 *
 * @Description
 */
class SysDeptPostRelation extends Model
{
    protected $guarded = [];

    /**
     * dept
     */
    public function dept()
    {
        return $this->belongsTo('App\Models\Admin\Base\Organization\SysDept', 'dept_id');
    }

    /**
     * 人员岗位中间表
     */
    public function relation()
    {
        return $this->hasMany('App\Models\Admin\Base\Organization\SysDeptRelation', 'post_relation_id', 'post_id');
    }

    /**
     * admin
     */
    public function post()
    {
        return $this->hasMany('App\Models\Admin\Base\Organization\SysDeptPost', 'id', 'post_relation_id');
    }

    /**
     * 用户
     */
    public function admin()
    {
        return $this->hasManyThrough(
            'App\Models\Admin\Admin\SysAdmin',
            'App\Models\Admin\Base\Organization\SysDeptRelation',
            'post_relation_id',
            'id',
            'admin_id'
        );
    }
}
