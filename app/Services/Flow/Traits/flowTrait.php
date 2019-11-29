<?php
namespace flow\Traits;

use Auth,Request;
use App\Models\Admin\Base\Flow\SysFlowInstanceRecords as Record;

/**
 * flow trait
 *
 * @Description
 * @author Hsu Lay
 * @since 20191128
 */
trait flowTrait{

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
	public function unpass($record_id){
        $auth = Auth::guard('admin')->user();
		$Record=Record::where(['emp_id'=>$auth->id])->where(["status"=>0])->findOrFail($record_id);

        //驳回
        Record::where(['entry_id'=>$Record->entry_id,'node_id'=>$Record->node_id,'circle'=>$Record->instance->circle,'status'=>0])->update([
            'status'=>-1,
            'auditor_id'=>$auth->id,
            'auditor_name'=>$auth->name,
            'auditor_dept'=>$auth->dept->dept_name,
            'content'=>Request::input('content',''),
        ]);

        $Record->entry()->update([
            'status'=>-1
        ]);

        //判断是否存在父进程
        if($Record->entry->pid>0){
            $Record->entry->parent_entry->update([
                'status'=>-1,
                'child'=>$Record->node_id
            ]);
        }

        $Record->entry->emp->notify(new \App\Notifications\Flowfy(Proc::find($Record->id)));
	}
}