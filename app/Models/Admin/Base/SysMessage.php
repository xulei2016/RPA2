<?php

namespace App\Models\Admin\Base;

use App\Models\Admin\Admin\SysAdmin;
use Illuminate\Database\Eloquent\Model;

class SysMessage extends Model
{
    public $timestamps = false;

    //黑名单，白名单
    protected $guarded = [];

    public function getTypeName()
    {
        return $this->belongsTo(SysMessageTypes::class,"type","id");
    }
}
