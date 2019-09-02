<?php

namespace App\Models\Admin\Base\CallCenter;

use Illuminate\Database\Eloquent\Model;

class SysManager extends Model
{
    protected $table = "sys_call_center_managers";

    public $fillable = ['group_id','sys_admin_id','nickname','work_number','label','desc'];
}