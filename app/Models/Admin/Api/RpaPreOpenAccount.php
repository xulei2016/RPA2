<?php

namespace App\Models\Admin\Api;

use Illuminate\Database\Eloquent\Model;

class RpaPreOpenAccount extends Model
{
    protected $table = "rpa_pre_open_accounts";

    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];
}
