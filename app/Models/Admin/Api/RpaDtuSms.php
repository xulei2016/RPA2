<?php

namespace App\Models\Admin\Api;

use Illuminate\Database\Eloquent\Model;

class RpaDtuSms extends Model
{
    public $table = "rpa_dtu_sms";
    
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];

}
