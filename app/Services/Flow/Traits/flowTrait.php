<?php
namespace App\Services\Flow\Traits;

use Auth,Request;
use App\Models\Admin\Base\Flow\SysFlowInstanceRecords as Record;

/**
 * flow trait
 *
 * @Description
 * @author Hsu Lay
 * @since 20191128
 */
trait FlowTrait{

    /**
     * pass function
     *
     * @param [type] $node_id
     * @return void
     * @Description
     */
	public function pass($node_id){
		(new static)->flowlink($node_id);
	}

    
    /**
     * unpass function
     *
     * @param [type] $record_id
     * @return void
     * @Description
     */
    public function unpass($record_id)
    {
        $auth = Auth::guard('admin')->user();
		$Record=Record::where(['user_id'=>$auth->id])->where(["status"=>0])->findOrFail($record_id);

        //驳回
        Record::where(['instance_id'=>$Record->instance_id, 'node_id'=>$Record->node_id, 'circle'=>$Record->instance->circle, 'status'=>0])->update([
            'status' => -1,
            'user_id' => $auth->id,
            'user_name' => $auth->name,
            'dept_name' => $auth->dept->dept_name,
            'content' => Request::input('content',''),
        ]);

        $Record->instance()->update([
            'status' => -1
        ]);

        //判断是否存在父进程
        if($Record->instance->pid>0){
            $Record->instance->parent_instance->update([
                'status' => -1,
                'child' => $Record->node_id
            ]);
        }

        //消息通知，队列
        // $Record->instance->user->notify(new \App\Notifications\Flowfy(Proc::find($Record->id)));
	}
}