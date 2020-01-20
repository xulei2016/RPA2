<?php

namespace App\Models\Index\CNode;

use Illuminate\Database\Eloquent\Model;

class RpaAccountFlows extends Model
{
    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];
}
