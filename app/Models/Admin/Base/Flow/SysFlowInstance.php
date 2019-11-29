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
     * flow function
     */
    public function flow(){
        return $this->belongsTo("App\Models\Admin\Base\Flow\SysFlow","flow_id");
    }

    /**
     * flow function 审批人/发起人
     */
    public function user(){
    	return $this->belongsTo("App\Models\Admin\Admin\SysAdmin","user_id");
    }

    /**
     * records function
     */
    public function records(){
    	return $this->hasMany("App\Models\Admin\Base\Flow\SysFlowInstanceRecords","instance_id");
    }

    /**
     * node function
     */
    public function node(){
    	return $this->belongsTo("App\Models\Admin\Base\Flow\SysFlowNode","node_id");
    }

    /**
     * instance_data
     */
    public function instance_data(){
        return $this->hasMany("App\Models\Admin\Base\Flow\SysFlowInstanceData","instance_id");
    }

    /**
     * parent_instance
     */
    public function parent_instance(){
        return $this->belongsTo('App\Models\Admin\Base\Flow\SysFlowInstance','parent_id');
    }

    /**
     * 子流程实例
     */
    public function children(){
        return $this->hasMany('App\Models\Admin\Base\Flow\SysFlowInstance','parent_id');
    }

    /**
     * instance_node
     */
    public function instance_node(){
        return $this->belongsTo('App\Models\Admin\Base\Flow\SysFlowNode','node_id');
    }

    /**
     * child_node
     */
    public function child_node(){
        return $this->belongsTo('App\Models\Admin\Base\Flow\SysFlowNode','child_id');
    }
}
