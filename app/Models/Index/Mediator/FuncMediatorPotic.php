<?php

namespace App\Models\Index\Mediator;

use Illuminate\Database\Eloquent\Model;

class FuncMediatorPotic extends Model
{
    protected $table = "func_mediator_potic";

    public $timestamps = false;

    //黑名单，白名单
    protected $guarded = [];
}
