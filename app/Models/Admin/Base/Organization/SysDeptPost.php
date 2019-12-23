<?php


namespace App\Models\Admin\Base\Organization;


use Illuminate\Database\Eloquent\Model;

/**
 * 组织架构-岗位
 * Class SysDeptPost
 * @package App\Models\Admin\Base\Organization
 */
class SysDeptPost extends Model
{
    protected $table = 'sys_dept_posts';

    public $timestamps = false;

    protected $guarded = [];
}