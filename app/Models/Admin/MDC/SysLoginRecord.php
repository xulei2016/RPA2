<?php

namespace App\Models\Admin\MDC;

use Illuminate\Database\Eloquent\Model;

class SysLoginRecord extends Model
{
    public $timestamps = false;

    //黑名单，白名单
    protected $guarded = [];
}
