<?php

namespace App\Models\Admin\Api;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    protected $table = "api_logs";

    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];
}
