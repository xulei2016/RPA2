<?php

namespace App\Models\Admin\Base\Flow;

use Illuminate\Database\Eloquent\Model;

/**
 * SysFlow
 * 
 * @description 流程模型
 */
class SysFlow extends Model
{
    //黑名单，白名单
    protected $guarded = [];

    /**
     * 多节点
     */
    public function getNodes()
    {
        return $this->hasMany('Models/Admin/Base/Flow/SysFlowNode', 'flow_id');
    }

    /**
     * 多实例
     */
    public function getFlowInstances()
    {
        return $this->hasMany('Models/Admin/Base/Flow/SysFlowInstance', 'flow_id');
    }
}
