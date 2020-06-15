<?php

namespace App\Models\Admin\Rpa\Kpi;

use Illuminate\Database\Eloquent\Model;

class RpaKpiVideoRecords extends Model
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
