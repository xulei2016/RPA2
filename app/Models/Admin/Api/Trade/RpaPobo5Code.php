<?php

namespace App\Models\Admin\Api\Trade;

use Illuminate\Database\Eloquent\Model;

class RpaPobo5Code extends Model
{
    public $table = 'rpa_pobo5_codes';
    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];
}
