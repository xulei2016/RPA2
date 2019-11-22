<?php

namespace App\Models\Admin\Base\Flow;

use Illuminate\Database\Eloquent\Model;

/**
 * SysFlowInstance
 *
 * @Description 流程实例
 */
class SysFlowInstance extends Model
{
    //黑名单，白名单
    protected $guarded = [];

    /**
     * 多节点
     */
    public function getInstanceNodes()
    {
        return $this->hasMany('Models/Admin/Base/Flow/SysFlowInstanceNode', 'flow_id');
    }
}
