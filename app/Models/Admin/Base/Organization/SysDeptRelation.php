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
     * 用户
     */
    public function admin()
    {
        return $this->hasManyThrough(
            'App\Models\Admin\Admin\SysAdmin',
            'App\Models\Admin\Base\Organization\SysDeptRelation',
            'post_relation_id',
            'admin_id',
            'id'
        );
    }

    /**
     * post
     */
    public function post()
    {
        return $this->hasMany('App\Models\Admin\Base\Organization\SysDeptRelation', 'post_relation_id');
    }
}
