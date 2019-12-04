<?php

namespace App\Models\Admin\Api\Trade;

use Illuminate\Database\Eloquent\Model;

class RpaTradeLoginRecord extends Model
{
    public $table = 'rpa_trade_loginRecord';
    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];
}
