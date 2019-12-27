<?php


namespace App\Models\Admin\Base\Organization;


use Illuminate\Database\Eloquent\Model;

/**
 * 人和岗位关系  onToMany
 * Class SysDeptRelation
 * @package App\Models\Admin\Base\Organization
 */
class SysDeptRelation extends Model
{
    protected $table = 'sys_dept_relations';

    protected $guarded = [];
}