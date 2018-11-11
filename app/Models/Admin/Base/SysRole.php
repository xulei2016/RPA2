<?php

namespace App\Models\Admin\Base;

use Illuminate\Database\Eloquent\Model;

class SysRole extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    public $timestamps = false;

    //黑名单，白名单
    // protected $fillable = ['name'];
    protected $guarded = [];

    /**
     * 获取全部权限
     */
    public function permissions()
    {
         return $this->hasMany('App\Models\Admin\Base\sysRoleHasPermission','role_id','id');
    }
}
