<?php

namespace App\models\admin\base;

use App\Models\Admin\Admin\SysAdmin;
use Illuminate\Database\Eloquent\Model;

class SysMail extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];

    //关联用户表
    public function admins()
    {
        return $this->belongsToMany(SysAdmin::class,'sys_user_mails','mid','uid');
    }
}
