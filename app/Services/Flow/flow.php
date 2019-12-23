<?php
namespace App\Services\Flow;

use DB,Auth;
use App\Models\Admin\Admin\SysAdmin;
use App\Models\Admin\Base\Flow\SysFlowLink;
use App\Models\Admin\Base\Flow\SysFlowInstance;
use App\Models\Admin\Base\Flow\SysFlowInstanceData;
use App\Models\Admin\Base\Flow\SysFlowNodeCondition as NodeConf;
use App\Models\Admin\Base\Flow\SysFlowInstanceRecords as Record;
use App\Services\Flow\Traits\FlowTrait;
use App\Services\Flow\FlowInterface;

/**
 * flow class
 *
 * @Description flow service
 * @author Hsu Lay
 * @since 20191128
 */
class Flow implements FlowInterface
{
	use FlowTrait;

    /**
     * getNodeAuditorIds function
     *
     * @param SysFlowInstance $SysFlowInstance
     * @param [int] $node_id
     * @return void
     * @Description 获得下一步审批人员工id
     */
	public static function getNodeAuditorIds(SysFlowInstance $Instance, $node_id){
		$auditor_ids=[];
		//查看是否自动选人
		if($flowlink = SysFlowLink::where('type','Sys')->where('node_id',$node_id)->first()){
			if($flowlink->auditor == '-1000'){
				//发起人
				$auditor_ids[] = $Instance->user_id;
			}

			if($flowlink->auditor == '-1001'){
				//发起人部门主管
				if(empty($Instance->user->dept)){
					return $auditor_ids;
                }
                $post_ids = json_decode($Instance->user->dept->post_ids, true);
                $auditor_ids = isset($post_ids['BMZG']) ? $post_ids['BMZG'] : [];
			}

			if($flowlink->auditor=='-1002'){
				//发起人部门经理
				if(empty($Instance->user->dept)){
					return $auditor_ids;
				}
				$post_ids = json_decode($Instance->user->dept->post_ids, true);
                $auditor_ids = isset($post_ids['BMJL']) ? $post_ids['BMZG'] : [];
            }
		}else{
            //查询节点
            if($flowLink = SysFlowLink::where('node_id',$node_id)->where('type','!=','Condition')->first())
            {
                $ids=explode(',', $flowLink->auditor);

                switch($flowLink->type)
                {
                    case 'user';     //指定员工

                        $auditor_ids=array_merge($auditor_ids, $ids);
                        
                        break;
                    case 'Dept';    //指定部门
                    
                        $user_ids=SysAdmin::whereIn('dept_id', $ids)->get()->pluck('id')->toArray();
        
                        $auditor_ids=array_merge($auditor_ids,$user_ids);

                        break;
                    case 'Role';//角色

                        $auditor_ids = DB::table('sys_model_has_roles')->whereIn('role_id', $ids)->get(['model_id'])->unique('model_id')->pluck('model_id')->toArray();
                        
                        $auditor_ids=array_merge($auditor_ids,$user_ids);

                        break;
                }
            }
		}

		return array_unique($auditor_ids);
	}

	/**
	 * [setFirstNodeAuditor 初始流转]
     * 
	 * @param [type] $Instance    [流程实例]
	 * @param [type] $flowlink [流程关联]
     * @description 设置初始节点
     * 
     * @if 查询流程发起是否需要审核（是否设置审核人）
     *     设置审核人，则对所有审核人发起一条待审核流程，任一用户审核通过即可
     * @else 创建发起记录，创建下一节点待审核流程
	 */
    public function setFirstNodeAuditor(SysFlowInstance $Instance, SysFlowLink $flowlink)
    {
		$node_id = $node_title = null;
	    if(!SysFlowLink::where('type','!=','Condition')->where('node_id', $flowlink->node_id)->first()){
            
	        //第一步未指定审核人 自动进入下一步操作
	        $Instance->records()->create([
	            'user_id'=>$Instance->user_id,
	            'user_name'=>$Instance->user->realName,
	            'dept_name'=>$Instance->user->dept->name,
	            'real_user_id'=>$Instance->user_id,
	            'real_user_name'=>$Instance->user->realName,
	            'real_user_dept'=>$Instance->user->dept->name,
	            'flow_id'=>$Instance->flow_id,
	            'node_id'=>$flowlink->node_id,
	            'node_title'=>$flowlink->node->node_title,
	            'status'=>9,
	            'circle'=>$Instance->circle,
	            // 'concurrence'=>time()
	        ]);

	        $auditor_ids=$this->getNodeAuditorIds($Instance,$flowlink->next_node_id);
	        $node_id=$flowlink->next_node_id;
	        $node_title=$flowlink->next_node->node_title;
	        $Instance->node_id = $flowlink->next_node_id;
	    }else{

	        $auditor_ids=$this->getNodeAuditorIds($Instance,$flowlink->node_id);
	        $node_id=$flowlink->node_id;
	        $node_title=$flowlink->node->node_title;
	        $Instance->node_id=$flowlink->node_id;
	    }

	    //步骤流转
	    //步骤审核人
	    $auditors=SysAdmin::whereIn('id',$auditor_ids)->get();
	    if($auditors->count()<1){
	        throw new \Exception("下一步骤未找到审核人", 1);
	    }
	    $time=time();
	    foreach($auditors as $v){
	        $Instance->records()->create([
	            'flow_id'=>$Instance->flow_id,
	            'node_id'=>$node_id,
	            'node_title'=>$node_title,
	            'user_id'=>$v->id,
	            'user_name'=>$v->realName,
	            'dept_name'=>$v->dept->name,
	            'status'=>0,
	            'circle'=>$Instance->circle,
	            // 'concurrence'=>$time
	        ]);
	    }
	    return $Instance->save();
	}

	/**
	 * [flowlink 流转]
     * 
	 * @param  [type] $node_id [description]
	 * @return [type] [description] 流程通过流转
     * 
     * @if 是否多路流转
     * 
     *      按照流转条件查找下一节点审核人，发起审核流程， 存在父流程则更新父流程中子流程进度字段
     * 
     * @else 是否存在子流程
     * 
     *      创建子流程，发起流程，更新父流程中子流程节点
     * 
     * @else 是否最后审核环节
     * 
     *      更新当前节点
     *      @if 是否存在父流程
     *          更新父流程，查看父流程模式是否同步结束或继续父流程步骤，进行操作
     * 
     *      @if 同时父流程是否最后环节，同理判断
     * 
     * @else 结束当前节点，创建下一节点审核人，是否存在父流程，更新父流程中子流程状态
     * 
     *      
	 */
    public function flowlink($node_id)
    {
        //当前用户存在未审批流程
	    $Record = Record::with('instance.user.dept')->where('user_id', Auth::guard('admin')->id())->where("status", 0)->findOrFail($node_id);

        //流程数据
        $flowlinks=SysFlowLink::where(['node_id'=>$Record->node_id,"type"=>"Condition"])->get();
        
        //是否多路流转
	    if($flowlinks->count() > 1){
	        //有条件 TODO 多个变量字段 待处理
            $var=NodeConf::where(['node_id'=>$Record->node_id])->first(); //$var->expression_field  $var->expression_field_value
            
	        //当前步骤判断的变量 需要根据 条件 去查当前工作流提交的表单数据 里的值
	        $value=SysFlowInstanceData::where(['instance_id'=>$Record->instance_id,'field_name'=>$var->expression_field])->value('field_value');

            $flowlink=null;

            //流转条件变量名
	        $field=$var->expression_field;
	        foreach($flowlinks as $v){
	            if(empty($v->expression)){
	                throw new \Exception('未设置流转条件，无法流转，请联系流程设置人员',1);
	            }

                //直接过 == 无条件
	            if($v->expression == '1'){
	            	$flowlink = $v;
	            	break;
	            }else{
                    //构造变量, 执行条件语句
                    $$field = $value;
                    eval('$res=('.$v->expression.');');
		            if($res){
		                $flowlink = $v;
		                break;
		            }
	            }
	        }

            //是否满足条件
	        if(empty($flowlink)){
	        	throw new \Exception('未满足流转条件，无法流转到下一步骤，请联系流程设置人员',1);
            }
            
            //查找验证节点审核人
	        $auditor_ids = $this->getNodeAuditorIds($Record->instance, $flowlink->next_node_id);
	        if(empty($auditor_ids)){
	        	throw new \Exception("下一步骤未找到审核人", 1);
	        }

            //获取用户
	        $auditors = SysAdmin::whereIn('id', $auditor_ids)->get();
	        if($auditors->count() < 1){
	            throw new \Exception("下一步骤未找到审核人", 1);
	        }

            $time = time();

            //添加审批初始记录
	        foreach($auditors as $v){
	            Record::create([
	                'instance_id'=>$Record->instance_id,
	                'flow_id'=>$Record->flow_id,
	                'node_id'=>$flowlink->next_node_id,
	                'node_title'=>$flowlink->next_node->node_title,
	                'user_id'=>$v->id,
	                'user_name'=>$v->realName,
	                'dept_name'=>$v->dept->name,
	                'circle'=>$Record->instance->circle,
	                'status'=>0,
	                'is_read'=>0,
	                // 'concurrence'=>$time
	            ]);
	        }

            //更新当前节点
	        $Record->instance->update([
	            'node_id'=>$flowlink->next_node_id
	        ]);

	        //判断是否存在父进程
	        if($Record->instance->parent_id > 0){
	            $Record->instance->parent_instance->update([
	                'child' => $flowlink->next_node_id
	            ]);
            }
            
            //通知队列
            $this->flowNotify($Record, $auditor_ids);

	    }else{
            $flowlink = $flowlinks->first();

	        if($flowlink->node->child_id > 0){
	            // 创建子流程
	            if(!$child_instance=SysFlowInstance::where(['parent_id'=>$Record->instance->id,'circle'=>$Record->instance->circle])->first()){
	                $child_instance=SysFlowInstance::create([
	                    'title'=>$Record->instance->title,
	                    'flow_id'=>$flowlink->node->child_id,
	                    'user_id'=>$Record->instance->user_id,
	                    'status'=>0,
	                    'parent_id'=>$Record->instance->id,
	                    'circle'=>$Record->instance->circle,
	                    'node_id'=>$flowlink->node_id,
	                    'instance_record_id'=>$Record->id,
	                ]);
	            }
	            
	            $child_flowlink=SysFlowLink::where(['flow_id'=>$flowlink->node->child_id,'type'=>'Condition'])->whereHas('node',function($query){
	                $query->where('position',0);
	            })->orderBy("sort","ASC")->first();

	            $this->setFirstNodeAuditor($child_instance, $child_flowlink);

	            $child_instance->parent_instance->update([
	                'child'=>$child_instance->node_id
	            ]);

	        }else{
                //最后一步
                if($flowlink->next_node_id == -1)
                {
	                $Record->instance()->update([
	                    'status'=>9,
	                    'node_id'=>$flowlink->node_id
	                ]);

	                //子流程结束
	                if($Record->instance->parent_id > 0){
                        if($Record->instance->instance_node->child_after == 1)
                        {
	                        //同时结束父流程
	                        $Record->instance->parent_instance->update([
	                            'status'=>9,
	                            'child'=>0
	                        ]);
	                    }else{
	                        //进入设置的父流程步骤
	                        if($Record->instance->instance_node->child_back_node > 0){
	                        	$this->goToNode($Record->instance->parent_instance,$Record->instance->instance_node->child_back_node);
	                            $Record->instance->parent_instance->node_id=$Record->instance->instance_node->child_back_node;
	                        }else{
	                        	//默认进入父流程步骤下一步
	                        	$parent_flowlink=SysFlowLink::where(['node_id'=>$Record->instance->instance_node->id,"type"=>"Condition"])->first();

	                        	//判断是否为最后一步
	                        	if($parent_flowlink->next_node_id == -1){
	                        		$Record->instance->parent_instance->update([
			                            'status'=>9,
			                            'child'=>0,
			                            'node_id'=>$Record->instance->enter_node->child_back_node
			                        ]);
			                        //流程结束通知
	    							// $Record->instance->user->notify(new \App\Notifications\Flowfy(Record::find($Record->id)));
	                        	}else{
	                        		$this->goToNode($Record->instance->parent_instance,$parent_flowlink->next_node_id);

	                                $Record->instance->parent_instance->node_id=$parent_flowlink->next_node_id;
	                                $Record->instance->parent_instance->status=0;
	                        	}
	                        }

	                        $Record->instance->parent_instance->child=0;
	                        
	                        $Record->instance->parent_instance->save();
	                    }
	                    
	                }else{
                        //流程结束通知
                        //通知队列

//                        dd('通缉你啦');
//	    				 $Record->instance->user->notify(new \App\Notifications\Flowfy(Proc::find($Record->id)));
	                }
	            }else{
	                $auditor_ids=$this->getNodeAuditorIds($Record->instance, $flowlink->next_node_id);
	                $auditors=SysAdmin::whereIn('id',$auditor_ids)->get();

	                if($auditors->count() < 1){
	                    throw new \Exception("下一步骤未找到审核人", 1);
                    }
                    
	                foreach($auditors as $v){
	                    Record::create([
	                        'instance_id'=>$Record->instance_id,
	                        'flow_id'=>$Record->flow_id,
	                        'node_id'=>$flowlink->next_node_id,
	                        'node_title'=>$flowlink->next_node->node_title,
	                        'user_id'=>$v->id,
	                        'user_name'=>$v->realName,
	                        'dept_name'=>$v->dept->name,
	                        'circle'=>$Record->instance->circle,
	                        'status'=>0,
	                        'is_read'=>0
                        ]);
	                }

	                $Record->instance->update([
	                    'node_id'=>$flowlink->next_node_id
	                ]);

	                //判断是否存在父进程
	                if($Record->instance->parent_id > 0){
	                    $Record->instance->parent_instance->update([
	                        'child'=>$flowlink->next_node_id
	                    ]);
                    }
                    
                    //通知队列
                    $this->flowNotify($Record, $auditor_ids);
	            }
	        }
	    }

        //当前流程记录更新
	    Record::where(['id'=>\Request::input('id'), 'instance_id'=>$Record->instance_id, 'node_id'=>$Record->node_id, 'circle'=>$Record->instance->circle, 'status'=>0])->update([
	        'status'=>9,
	        'user_id'=>\Auth::guard('admin')->id(),
	        'user_name'=>\Auth::guard('admin')->user()->realName,
	        'dept_name'=>\Auth::guard('admin')->user()->dept->name,
	        'remark'=>\Request::input('remark',''),
        ]);
        
        return true;

	}

	/**
	 * [goToNode 前往固定流程步骤]
	 * @param  [type] $instance      [description]
	 * @param  [type] $node_id [description]
	 * @return [type]             [description]
	 */
    protected function goToNode(SysFlowInstance $instance,$node_id)
    {
	    $auditor_ids=$this->getNodeAuditorIds($instance,$node_id);

	    $auditors=SysAdmin::whereIn('id',$auditor_ids)->get();

	    if($auditors->count() < 1){
	        throw new \Exception("下一步骤未找到审核人", 1);
	    }
        $time=time();
        
	    foreach($auditors as $v){
	        Record::create([
	            'instance_id'=>$instance->id,
	            'flow_id'=>$instance->flow_id,
	            'node_id'=>$node_id,
	            'node_title'=>SysFlowNode::find($node_id)->node_title,
	            'user_id'=>$v->id,
	            'user_name'=>$v->realName,
	            'dept_name'=>$v->dept->name,
	            'circle'=>$instance->circle,
	            'status'=>0,
	            'is_read'=>0,
	            'time'=>$time,
	        ]);
        }
        
        return true;
    }

    /**
     * flowNotify function
     *
     * @param string $content
     * @param array $ids
     * @return void
     * @Description
     */
    protected function flowNotify(Record $record, array $ids)
    {
        \App\Models\Admin\Base\SysMessage::create([
            'title' => $record->flow->title,
            'content' => $record->instance->title."<br/><a href='javascript:void(0);' url='/admin/sys_flow_mine/{$record->id}' onclick='operation($(this));'>您有新的流程等待审批，点击查看。</a>",
            'type' => 4,
            'mode' => 1,
            'user' => implode(',', $ids)
        ]);
    }
    
}