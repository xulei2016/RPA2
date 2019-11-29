<?php

namespace App\Models\Admin\Base\Flow;

use Illuminate\Database\Eloquent\Model;

class SysFlowLink extends Model
{
    //黑名单，白名单
    protected $guarded = [];

    /**
     * node function
     */
    public function node(){
    	return $this->belongsTo('App\Models\Admin\Base\Flow\SysFlowNode','node_id');
    }

    /**
     * next_node function
     */
    public function next_node(){
    	return $this->belongsTo('App\Models\Admin\Base\Flow\SysFlowNode','next_node_id');
    }
}
