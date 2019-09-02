<?php

namespace App\Models\Admin\Base\CallCenter;

use Illuminate\Database\Eloquent\Model;

class SysRecord extends Model
{
    protected $table = "sys_call_center_records";

    protected $fillable = ['manager_id','customer_id'];
}