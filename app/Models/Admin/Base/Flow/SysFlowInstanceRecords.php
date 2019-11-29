<?php

namespace App\Models\Admin\Base\Flow;

use Illuminate\Database\Eloquent\Model;

/**
 * SysFlowInstanceRecords
 *
 * @Description 流程节点实例
 */
class SysFlowInstanceRecords extends Model
{
    //黑名单，白名单
    protected $guarded = [];

    /**
     * user
     */
    public function user(){
    	return $this->belongsTo("App\Models\Admin\Admin\SysAdmin","user_id");
    }

    /**
     * instance
     */
    public function instance(){
    	return $this->belongsTo("App\Models\Admin\Base\Flow\SysFlowInstance","instance_id");
    }

    /**
     * node
     */
    public function node(){
    	return $this->belongsTo("App\Models\Admin\Base\Flow\SysFlowNode","node_id");
    }

    /**
     * flow
     */
    public function flow(){
    	return $this->belongsTo("App\Models\Admin\Base\Flow\SysFlow","flow_id");
    }

    /**
     * records
     */
    public function records(){
    	return $this->hasMany('App\Models\Admin\Base\Flow\SysFlowInstanceRecords','instance_id');
    }
}
