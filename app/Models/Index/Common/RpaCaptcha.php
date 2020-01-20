<?php

namespace App\Models\Index\Common;

use Illuminate\Database\Eloquent\Model;

class RpaCaptcha extends Model
{
    protected $table = "rpa_captcha";

    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];
}
