<?php

namespace App\Models\Admin\Base\Flow;

use Illuminate\Database\Eloquent\Model;

/**
 * SysFlowInstanceNode
 *
 * @Description 流程节点实例
 */
class SysFlowInstanceNode extends Model
{
    //黑名单，白名单
    protected $guarded = [];

    
    /**
     * 多流程
     */
    public function getFlows()
    {
        return $this->belongsTo('Models/Admin/Base/Flow/SysFlowInstance', 'flow_id');
    }

    /**
     * 多节点
     */
    public function getFlowInstances()
    {
        return $this->belongsTo('Models/Admin/Base/Flow/SysFlowInstance', 'flow_id');
    }
}
