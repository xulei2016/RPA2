<?php

namespace App\Models\Admin\Rpa;

use Illuminate\Database\Eloquent\Model;

class rpa_clock_list extends Model
{
    public $table = "rpa_clock_list";
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    public $timestamps = false;

    //黑名单，白名单
    protected $guarded = [];
}
