<?php

namespace App\Models\Admin\Rpa;

use Illuminate\Database\Eloquent\Model;

class rpa_timetasks extends Model
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
