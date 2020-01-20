<?php

namespace App\Models\Index\Mediator;

use App\Models\Admin\Base\Organization\SysDept;
use Illuminate\Database\Eloquent\Model;

class FuncMediatorFlow extends Model
{
    protected $table = "func_mediator_flow";

    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];

    public function dept()
    {
        return $this->belongsTo(SysDept::class,'dept_id','id');
    }

    public function info()
    {
        return $this->belongsTo( FuncMediatorInfo::class,'uid','id');
    }
}
