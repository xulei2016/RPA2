<?php

namespace App\Models\Admin\Api\Revisit\Customer;

use Illuminate\Database\Eloquent\Model;

class RpaRevisitCustomerRecords extends Model
{
    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];
}
