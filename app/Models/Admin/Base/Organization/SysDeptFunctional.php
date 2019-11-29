<?php

namespace App\Models\Admin\Base\Organization;

use Illuminate\Database\Eloquent\Model;

/**
 * 组织架构-职能
 * Class SysDeptFunctional
 * @package App\Models\Admin\Base\Organization
 */
class SysDeptFunctional extends Model
{
    protected $guarded = [];

    //岗位
    function posts(){
        return $this->belongsTo('App\Models\Admin\Organization\SysDeptPost', 'post_id');
    }
}
