<?php

namespace App\Models\Admin\Base\Sys\SMS;

use Illuminate\Database\Eloquent\Model;

class SysSmsLog extends Model
{
    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];
}
