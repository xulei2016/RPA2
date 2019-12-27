<?php

namespace App\Models\Index\Mediator;

use Illuminate\Database\Eloquent\Model;

class FuncMediatorStep extends Model
{
    protected $table = "func_mediator_step";

    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];
}
