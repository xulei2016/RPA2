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

    /**
     * 获取下个节点id
     * @param $flowId
     * @param $nodeId
     * @return int
     */
    public static function getNextNodes($flowId, $nodeId){
        $result = self::where([
            ['node_id', '=', $nodeId],
            ['flow_id', '=', $flowId],
            ['type', '=', 'Condition'],
        ])->first();
        if($result) {
            return $result->next_node_id;
        } else {
            return 0;
        }
    }
}
