<?php

namespace App\Models\Admin\Api;

use Illuminate\Database\Eloquent\Model;

class RpaDtuSms extends Model
{
    protected $table = "rpa_dtu_sms";
    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];

}
