<?php

namespace App\Models\Admin\Api;

use Illuminate\Database\Eloquent\Model;

class RpaCrmFlow extends Model
{
    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];

}
