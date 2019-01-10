<?php

namespace App\Models\Admin\Func;

use Illuminate\Database\Eloquent\Model;

class rpa_jjrvis extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    public $timestamps = false;

    //黑名单，白名单
    protected $guarded = [];
}
