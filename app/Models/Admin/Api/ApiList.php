<?php

namespace App\Models\Admin\Api;

use Illuminate\Database\Eloquent\Model;

class ApiList extends Model
{
    protected $table = "rpa_api_ips";

    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];
}
