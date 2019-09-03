<?php

namespace App\Models\Admin\Base\CallCenter;

use Illuminate\Database\Eloquent\Model;

class SysCustomer extends Model
{
    protected $table = "sys_call_center_customers";

    public $timestamps = false;

    protected $fillable = ['client','ip','jybm','zjqy','updated_at','sxf','bzj','gtsj','card','khrq','name','zjzh','khh','created_at'];
}