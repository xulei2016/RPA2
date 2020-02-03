<?php

namespace App\Models\Index\Mediator;

use App\Models\Admin\Base\Organization\SysDept;
use Illuminate\Database\Eloquent\Model;

class FuncMediatorChangeList extends Model
{
    protected $table = "func_mediator_changelist";

    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];
}
