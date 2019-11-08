<?php

namespace App\Models\Admin\Base;

use Illuminate\Database\Eloquent\Model;

class SysVersionUpdate extends Model
{
    protected $table = 'sys_version_updates';

    public $timestamps = true;

    protected $guarded = ['_method'];
}