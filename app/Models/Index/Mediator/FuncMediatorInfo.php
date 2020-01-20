<?php

namespace App\Models\Index\Mediator;

use Illuminate\Database\Eloquent\Model;

class FuncMediatorInfo extends Model
{
    protected $table = "func_mediator_info";
    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];
}
