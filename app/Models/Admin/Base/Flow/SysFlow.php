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
        return $this->hasMany('App\Models\Admin\Base\Flow\SysFlowNode', 'flow_id');
    }

    /**
     * 多实例
     */
    public function getFlowInstances()
    {
        return $this->hasMany('App\Models\Admin\Base\Flow\SysFlowInstance', 'flow_id');
    }

    /**
     * template 模板
     *
     * @return void
     */
    public function template(){
    	return $this->belongsTo('App\Models\Admin\Base\Flow\SysFlowTemplate','template_id');
    }

    /**
     * 节点记录
     *
     * @return void
     */
    public function nodeRecords(){
        return $this->hasMany('App\Models\Admin\Base\Flow\SysFlowRecords','node_id');
    }

    /**
     * getGroups function
     *
     * @return void
     * @Description
     */
    public function getGroups(){
    	return $this->belongsTo('App\Models\Admin\Base\Flow\SysFlowGroup','groupID');
    }
}
