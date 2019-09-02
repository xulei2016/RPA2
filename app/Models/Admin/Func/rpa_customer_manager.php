<?php

namespace App\Models\Admin\Func;

use Illuminate\Database\Eloquent\Model;

class rpa_customer_manager extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = "rpa_customer_manager";

    public $timestamps = false;

    //黑名单，白名单
    protected $guarded = [];
}
