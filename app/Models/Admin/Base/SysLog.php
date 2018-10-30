<?php

namespace App\models\admin\Base;

use Illuminate\Database\Eloquent\Model;

class SysLog extends Model
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
}
