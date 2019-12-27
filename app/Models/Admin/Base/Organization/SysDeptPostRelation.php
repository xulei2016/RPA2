<?php


namespace App\Models\Admin\Base\Organization;


use Illuminate\Database\Eloquent\Model;

/**
 * 组织结构-部门岗位关系表
 * Class SysDeptPostRelation
 * @package App\Models\Admin\Base\Organization
 */
class SysDeptPostRelation extends Model
{
    protected $table = "sys_dept_post_relations";

    protected $guarded = [];
}