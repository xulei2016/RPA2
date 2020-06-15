<?php

namespace App\Models\Admin\Api\Tmp;

use Illuminate\Database\Eloquent\Model;

class TmpOcrApiCount extends Model
{
    public $timestamps = false;

    //黑名单，白名单
    protected $guarded = [];
}
