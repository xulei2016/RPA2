<?php

namespace App\Models\Admin\Base\CallCenter;

use Illuminate\Database\Eloquent\Model;

class SysTemplate extends Model
{
    protected $table = "sys_call_center_message_templates";

    public $timestamps = false;

    protected $fillable = ['content', 'category', 'sort', 'keyword','answer'];
}