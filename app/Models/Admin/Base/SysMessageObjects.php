<?php

namespace App\Models\Admin\Base;

use Illuminate\Database\Eloquent\Model;

class SysMessageObjects extends Model
{
    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];
}
