<?php

namespace App\Models\Admin\Base;

use App\Models\Admin\Admin\SysAdmin;
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

    public function users()
    {
        return $this->belongsToMany(SysAdmin::class,'sys_model_has_roles',"model_id","role_id");
    }
}
