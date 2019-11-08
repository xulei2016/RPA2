<?php

namespace App\Models\Admin\Func;

use Illuminate\Database\Eloquent\Model;

class rpa_customer_videos extends Model
{
    //自动更新timestamps
    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];
}
