<?php

namespace App\Http\Controllers\Admin\Base\Flow;

use DB;
use Illuminate\Http\Request;
use App\Models\Admin\Admin\SysAdmin;
use App\Models\Admin\Base\Flow\SysFlow;
use App\Models\Admin\Base\Flow\SysFlowLink;
use App\Models\Admin\Base\Flow\SysFlowNode;
use App\Models\Admin\Base\Organization\SysDeptPost;
use App\Models\Admin\Base\Organization\SysDept as Dept;
use App\Http\Controllers\Base\BaseAdminController;

/**
 * SysFlowLinkController class
 *
 * @Description 流程流转中心
 */
class FlowLinkController extends BaseAdminController
{
    /**
     * dept function
     *
     * @param Request $request
     * @return void
     * @Description 部门
     */
    public function dept(Request $request){
        $dept = Dept::all();
        $depts_json = json_encode($dept->toArray());
        $depts = Dept::recursion($dept);
        return view('Admin.Base.Flow.permission.dept')->with(compact('depts','depts_json'));
    }

    //角色 TODO
    public function role(Request $request){
        return view('Admin.Base.Flow.permission.role');
    }

    /**
     * select emp
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function emp(Request $request,$id){
        $depts=Dept::recursion(Dept::get());
        $posts = SysDeptPost::get();
        $emps=SysAdmin::get(['id', 'realName', 'dept_id']);
        $emps_json = json_encode($emps->toArray());
        //当前节点
        $node=SysFlowNode::findOrFail($id);
        //当前选择员工
        $select_emps=SysAdmin::whereIn('id',explode(',',SysFlowLink::where('type','Emp')->where('node_id',$node->id)->value('auditor')))->get();
        return view('Admin.Base.Flow.permission.emp')->with(compact('depts','emps','select_emps','posts', 'emps_json'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            DB::beginTransaction();
        
            //保存流程设计
            $flow_id = $id;

            $node_info=json_decode($request->input('node_info',[]),true);

            if(!empty($node_info)){
                $flow=SysFlow::findOrFail($id);

                //更新flow 表 jsplumb json数据
                $jsplumb=json_decode($flow->jsplumb,true);
                $jsplumb['total']=count($node_info);
                foreach($node_info as $k=>$v){
                    //更新flow 表 jsplumb json数据
                    foreach($jsplumb['list'] as $i=>$j){
                        if($k==$j['id']){
                            $node=SysFlowNode::where(['id'=>$k])->first();
                            $node->update([
                                'style'=>'width:'.$node->style_width.'px;height:'.$node->style_width.'px;line-height:30px;color:'.$node->style_color.';left:'.$v['left'].'px;top:'.$v['top'].'px;',
                                'position_left'=>$v['left'].'px',
                                'position_top'=>$v['top'].'px'
                            ]);
                            $jsplumb['list'][$i]['node_to']=implode(',',$v['node_to']);
                            $jsplumb['list'][$i]['style']='width:'.$node->style_width.'px;height:'.$node->style_height.'px;line-height:30px;color:'.$node->style_color.';left:'.$v['left'].'px;top:'.$v['top'].'px;';
                        }
                    }

                    //更新流程轨迹 SysFlowLink表 type=Condition
                    $old_node_ids=SysFlowLink::where(['flow_id'=>$id,'type'=>'Condition','node_id'=>$k])->pluck('next_node_id')->toArray();

                    if(!empty($v['node_to'])){
                        //连线节点是否变动变动
                        if($v['node_to']!=$old_node_ids){
                            //新增连线
                            $adds=array_diff($v['node_to'],$old_node_ids);
                            foreach($adds as $a){
                                SysFlowLink::create([
                                    'flow_id'=>$id,
                                    'type'=>'Condition',
                                    'node_id'=>$k,
                                    'next_node_id'=>$a,
                                    'sort'=>100
                                ]);
                            }

                            //删除多余的连线
                            $dels=array_diff($old_node_ids,$v['node_to']);
                            SysFlowLink::where(['flow_id'=>$id,'type'=>'Condition','node_id'=>$k])->whereIn('next_node_id',$dels)->delete();
                        }
                    }else{
                        if(count($old_node_ids) > 1){
                            //只保留一个
                            $old_id=array_pop($old_node_ids);

                            SysFlowLink::where(['flow_id'=>$id,'type'=>'Condition','node_id'=>$k])->whereIn('next_node_id',$old_node_ids)->delete();
                            SysFlowLink::where(['flow_id'=>$id,'type'=>'Condition','node_id'=>$old_id])->update([
                                'next_node_id' => -1,
                            ]);
                        }else{
                            if(count($old_node_ids) == 1){
                            // if(SysFlowLink::where(['flow_id'=>$id,'type'=>'Condition','node_id'=>$k])->first()){
                                SysFlowLink::where(['flow_id'=>$id,'type'=>'Condition','node_id'=>$k])->update([
                                    'next_node_id'=>-1,
                                ]);
                            }else{
                                SysFlowLink::create([
                                    'flow_id'=>$id,
                                    'type'=>'Condition',
                                    'node_id'=>$k,
                                    'next_node_id'=>-1,
                                    'sort'=>100
                                ]);
                            }
                        }
                    }
                }
                $flow->jsplumb=json_encode($jsplumb);
                $flow->is_publish=0;
                $flow->save();
            }

            DB::commit();
            return response()->json(['status_code'=>0,'message'=>'更新成功']);
        }catch(\Eexception $e){
            DB::rollabck();
            dd($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
