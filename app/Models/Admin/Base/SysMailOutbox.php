<?php

namespace App\app\models\admin\base;

use Illuminate\Database\Eloquent\Model;

class SysMailOutbox extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];
}
