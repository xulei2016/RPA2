<?php

namespace App\Models\Admin\Base\Flow;

use Illuminate\Database\Eloquent\Model;

/**
 * SysFlowNodeCondition class
 *
 * @Description
 * @author Hsu Lay
 */
class SysFlowNodeCondition extends Model
{
    public $timestamps = false;

    //黑名单，白名单
    protected $guarded = [];
}
