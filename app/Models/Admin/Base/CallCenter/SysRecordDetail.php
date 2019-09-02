<?php

namespace App\Models\Admin\Base\CallCenter;

use Illuminate\Database\Eloquent\Model;

class SysRecordDetail extends Model
{
    protected $table = "sys_call_center_record_details";

    public $timestamps = false;

    protected $fillable = ['manager_id', 'customer_id','record_id', 'content', 'sender', 'type'];
}