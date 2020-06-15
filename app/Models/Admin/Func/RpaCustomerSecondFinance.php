<?php

namespace App\Models\Admin\Func;

use Illuminate\Database\Eloquent\Model;

class RpaCustomerSecondFinance extends Model
{
    protected $table = "rpa_customer_second_finances";

    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];

}
