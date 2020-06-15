<?php

namespace App\Models\Admin\Api\Tmp;

use Illuminate\Database\Eloquent\Model;

class TmpYingqiChange extends Model
{
    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];
}
