<?php

namespace App\Models\Admin\Api;

use Illuminate\Database\Eloquent\Model;

class RpaFlow extends Model
{
    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(RpaCustomerInfo::class,"fundAccount","fundAccount");
    }
}
