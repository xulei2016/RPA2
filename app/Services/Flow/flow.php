<?php
namespace flow;

use DB,Auth;
use App\Models\Admin\Admin\SysAdmin;
use App\Models\Admin\Base\Flow\SysFlowLink;
use App\Models\Admin\Base\Flow\SysFlowInstance;
use App\Models\Admin\Base\Flow\SysFlowInstanceData;
use App\Models\Admin\Base\Flow\SysFlowNodeCondition as NodeConf;
use App\Models\Admin\Base\Flow\SysFlowInstanceRecords as Record;
use flow\Traits\flowTrait;

/**
 * flow class
 *
 * @Description flow service
 * @author Hsu Lay
 * @since 20191128
 */
class flow implements flowInterface
{
	use flowTrait;

    /**
     * getNodeUserIds function
     *
     * @param SysFlowInstance $SysFlowInstance
     * @param [int] $node_id
     * @return void
     * @Description 获得下一步审批人员工id
     */
	protected function getNodeUserIds(SysFlowInstance $Instance, $node_id){
		$auditor_ids=[];
		//查看是否自动选人
		if($flowlink = SysFlowLink::where('type','Sys')->where('node_id',$node_id)->first()){
			if($flowlink->auditor == '-1000'){
				//发起人
				$auditor_ids[] = $Instance->user_id;
			}

			if($flowlink->auditor=='-1001'){
				//发起人部门主管
				if(empty($Instance->user->dept)){
					return $auditor_ids;
				}
				$auditor_ids[]=$Instance->user->relation->post->BMZG;
			}

			if($flowlink->auditor=='-1002'){
				//发起人部门经理
				if(empty($Instance->user->dept)){
					return $auditor_ids;
				}
				$auditor_ids[]=$Instance->user->relation->post->BMJL;
			}
		}else{
			//并行
			if($flowlink=SysFlowLink::where('type','Emp')->where('node_id',$node_id)->first()){
				//指定员工
				$auditor_ids=array_merge($auditor_ids,explode(',',$flowlink->auditor));
			}

			if($flowlink=SysFlowLink::where('type','Dept')->where('node_id',$node_id)->first()){
				//指定部门
				$dept_ids=explode(',',$flowlink->auditor);

				$emp_ids=SysAdmin::whereIn('dept_id',$dept_ids)->get()->pluck('id')->toArray();

				$auditor_ids=array_merge($auditor_ids,$emp_ids);
			}

			if($flowlink=SysFlowLink::where('type','Role')->where('node_id',$node_id)->first()){
                //指定角色,
                //todo
			}
		}

		return array_unique($auditor_ids);

	}

	/**
	 * [setFirstNodeAuditor 初始流转]
     * 
	 * @param [type] $Instance    [流程实例]
	 * @param [type] $flowlink [流程关联]
	 */
    public function setFirstNodeAuditor(SysFlowInstance $Instance, SysFlowLink $flowlink)
    {
		$node_id = $node_name = null;
	    if(!SysFlowLink::where('type','!=','Condition')->where('node_id',$flowlink->node_id)->first()){
	        //第一步未指定审核人 自动进入下一步操作
	        $Instance->records()->create([
	            'user_id'=>$Instance->user_id,
	            'user_name'=>$Instance->user->name,
	            'user_dept'=>$Instance->user->dept->dept_name,
	            'real_user_id'=>$Instance->user_id,
	            'real_user_name'=>$Instance->user->name,
	            'real_user_dept'=>$Instance->user->dept->dept_name,
	            'flow_id'=>$Instance->flow_id,
	            'node_id'=>$flowlink->node_id,
	            'node_name'=>$flowlink->node->node_name,
	            'status'=>9,
	            'circle'=>$Instance->circle,
	            'concurrence'=>time()
	        ]);

	        $auditor_ids=$this->getNodeAuditorIds($Instance,$flowlink->next_node_id);

	        $node_id=$flowlink->next_node_id;
	        $node_name=$flowlink->next_node->node_name;
	        $Instance->node_id = $flowlink->next_node_id;
	    }else{
	        $auditor_ids=$this->getNodeAuditorIds($Instance,$flowlink->node_id);

	        $node_id=$flowlink->node_id;
	        $node_name=$flowlink->node->node_name;

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
	            'node_name'=>$node_name,
	            'user_id'=>$v->id,
	            'user_name'=>$v->name,
	            'dept_name'=>$v->dept->dept_name,
	            'status'=>0,
	            'circle'=>$Instance->circle,
	            'concurrence'=>$time
	        ]);
	    }

	    $Instance->save();
	}

	/**
	 * [flowlink 流转]
     * 
	 * @param  [type] $node_id [description]
	 * @return [type] [description]
	 */
	public function flowlink($node_id){
	    $Record=Record::with('instance.user.dept')->where(['user_id'=>Auth::guard('admin')->id()])->where(["status"=>0])->findOrFail($node_id);

        //是否多路流转
	    if(SysFlowLink::where(['node_id'=>$Record->node_id,"type"=>"Condition"])->count() > 1){
	        //有条件 TODO 多个变量字段 待处理
            $var=NodeConf::where(['node_id'=>$Record->node_id])->first(); //$var->expression_field  $var->expression_field_value
            
	        //当前步骤判断的变量 需要根据 条件 去查当前工作流提交的表单数据 里的值
	        $value=SysFlowInstanceData::where(['instance_id'=>$Record->instance_id,'field_name'=>$var->expression_field])->value('field_value');

            //
	        $flowlinks=SysFlowLink::where(['node_id'=>$Record->node_id,"type"=>"Condition"])->get();
	        // $$var->expression_field_value=$var->expression_field_value;
            $flowlink=null;

            //流转条件变量名
	        $field=$var->expression_field;
	        foreach($flowlinks as $v){
	            if(empty($v->expression)){
	                throw new \Exception('未设置流转条件，无法流转，请联系流程设置人员',1);
	            }

                //直接过 == 无条件
	            if($v->expression=='1'){
	            	$flowlink=$v;
	            	break;
	            }else{
                    //构造变量, 执行条件语句
	            	$$field=$value;
		            eval('$res='.$v->expression.';'); //$res = $day > 3 ;
		            if($res){
		                $flowlink=$v;
		                break;
		            }
	            }
	        }

	        if(empty($flowlink)){
	        	throw new \Exception('未满足流转条件，无法流转到下一步骤，请联系流程设置人员',1);
	        }

            //查找验证节点审核人
	        $auditor_ids=$this->getNodeAuditorIds($Record->instance,$flowlink->next_node_id);
	        if(empty($auditor_ids)){
	        	throw new \Exception("下一步骤未找到审核人", 1);
	        }

	        $auditors=SysAdmin::whereIn('id',$auditor_ids)->get();
	        if($auditors->count()<1){
	            throw new \Exception("下一步骤未找到审核人", 1);
	        }

            $time=time();
            //添加审批初始记录
	        foreach($auditors as $v){
	            Record::create([
	                'instance_id'=>$Record->instance_id,
	                'flow_id'=>$Record->flow_id,
	                'node_id'=>$flowlink->next_node_id,
	                'node_name'=>$flowlink->next_node->node_name,
	                'user_id'=>$v->id,
	                'user_name'=>$v->name,
	                'dept_name'=>$v->dept->dept_name,
	                'circle'=>$Record->instance->circle,
	                'status'=>0,
	                'is_read'=>0,
	                'concurrence'=>$time
	            ]);
	        }

	        $Record->instance->update([
	            'node_id'=>$flowlink->next_node_id
	        ]);

	        //判断是否存在父进程
	        if($Record->instance->pid > 0){
	            $Record->instance->parent_instance->update([
	                'child'=>$flowlink->next_node_id
	            ]);
	        }

	    }else{
	        $flowlink=SysFlowLink::where(['node_id'=>$Record->node_id,"type"=>"Condition"])->first();

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

	            $this->setFirstNodeAuditor($child_instance,$child_flowlink);

	            $child_instance->parent_instance->update([
	                'child'=>$child_instance->node_id
	            ]);

	        }else{
                //最后一步
	            if($flowlink->next_node_id == -1){
	                $Record->instance()->update([
	                    'status'=>9,
	                    'node_id'=>$flowlink->node_id
	                ]);

	                //子流程结束
	                if($Record->instance->parent_id > 0){
	                    if($Record->instance->instance_node->child_after == 1){
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
	                        	$parent_flowlink=Flowlink::where(['node_id'=>$Record->instance->instance_node->id,"type"=>"Condition"])->first();

	                        	//判断是否为最后一步
	                        	if($parent_flowlink->next_node_id==-1){
	                        		$Record->instance->parent_instance->update([
			                            'status'=>9,
			                            'child'=>0,
			                            'node_id'=>$Record->instance->enter_node->child_back_node
			                        ]);
			                        //流程结束通知
	    							$Record->instance->emp->notify(new \App\Notifications\Flowfy(Proc::find($Record->id)));
	                        	}else{
	                        		$this->goToProcess($Record->instance->parent_instance,$parent_flowlink->next_node_id);

	                                $Record->instance->parent_instance->node_id=$parent_flowlink->next_node_id;
	                                $Record->instance->parent_instance->status=0;
	                        	}
	                        }

	                        $Record->instance->parent_instance->child=0;
	                        
	                        $Record->instance->parent_instance->save();
	                    }
	                    
	                }else{
	                	//流程结束通知
	    				$Record->instance->emp->notify(new \App\Notifications\Flowfy(Proc::find($Record->id)));
	                }
	            }else{
	                //'instance_id','flow_id','node_id','emp_id','status','content','is_read'
	                $auditor_ids=$this->getProcessAuditorIds($Record->instance,$flowlink->next_node_id);
	                $auditors=Emp::whereIn('id',$auditor_ids)->get();

	                if($auditors->count()<1){
	                    throw new \Exception("下一步骤未找到审核人", 1);
	                }
	                foreach($auditors as $v){
	                    Proc::create([
	                        'instance_id'=>$Record->instance_id,
	                        'flow_id'=>$Record->flow_id,
	                        'node_id'=>$flowlink->next_node_id,
	                        'node_name'=>$flowlink->next_node->node_name,
	                        'emp_id'=>$v->id,
	                        'emp_name'=>$v->name,
	                        'dept_name'=>$v->dept->dept_name,
	                        'circle'=>$Record->instance->circle,
	                        'status'=>0,
	                        'is_read'=>0
	                    ]);
	                }

	                $Record->instance->update([
	                    'node_id'=>$flowlink->next_node_id
	                ]);

	                //判断是否存在父进程
	                if($Record->instance->pid>0){
	                    $Record->instance->parent_instance->update([
	                        'child'=>$flowlink->next_node_id
	                    ]);
	                }
	            }
	        }
	    }

	    Proc::where(['instance_id'=>$Record->instance_id,'node_id'=>$Record->node_id,'circle'=>$Record->instance->circle,'status'=>0])->update([
	        'status'=>9,
	        'auditor_id'=>\Auth::id(),
	        'auditor_name'=>\Auth::user()->name,
	        'auditor_dept'=>\Auth::user()->dept->dept_name,
	        'content'=>\Request::input('content',''),
	    ]);

	}

	/**
	 * [goToProcess 前往固定流程步骤]
	 * @param  [type] $instance      [description]
	 * @param  [type] $node_id [description]
	 * @return [type]             [description]
	 */
	protected function goToProcess(Entry $instance,$node_id){
	    $auditor_ids=$this->getProcessAuditorIds($instance,$node_id);

	    $auditors=Emp::whereIn('id',$auditor_ids)->get();

	    if($auditors->count()<1){
	        throw new \Exception("下一步骤未找到审核人", 1);
	    }
	    $time=time();
	    foreach($auditors as $v){
	        Proc::create([
	            'instance_id'=>$instance->id,
	            'flow_id'=>$instance->flow_id,
	            'node_id'=>$node_id,
	            'node_name'=>Process::find($node_id)->node_name,
	            'emp_id'=>$v->id,
	            'emp_name'=>$v->name,
	            'dept_name'=>$v->dept->dept_name,
	            'circle'=>$instance->circle,
	            'status'=>0,
	            'is_read'=>0,
	            'time'=>$time,
	        ]);
	    }
	}
}