<?php

namespace App\Http\Controllers\Admin\Base\Flow;

use DB;
use Illuminate\Http\Request;
use App\Models\Admin\Admin\SysAdmin;
use App\Models\Admin\Base\SysRole;
use App\Models\Admin\Base\Organization\SysDept as Dept;
use App\Models\Admin\Base\Flow\SysFlow;
use App\Models\Admin\Base\Flow\SysFlowNode;
use App\Models\Admin\Base\Flow\SysFlowLink;
use App\Models\Admin\Base\Flow\SysFlowTemplate;
use App\Models\Admin\Base\Flow\SysFlowNodeCondition as NodeConf;
use App\Http\Controllers\Base\BaseAdminController;

class NodeController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //开启事务
        DB::beginTransaction();
        try{
            //{"status":1,"msg":"success","info":{"id":"9036","flow_id":1660,"node_name":"\u65b0\u5efa\u6b65\u9aa4","node_to":"","icon":"","style":"left:1105px;top:162px;color:#0e76a8;"}}
            $data=$request->all();

            $flow=SysFlow::findOrFail($data['flow_id']);

            // {"total":9,"list":[{"id":"9023","flow_id":"1660","node_name":"\u65b0\u5efa\u6b65\u9aa4","node_to":"","icon":"icon-lock","style":"width:30px;height:30px;line-height:30px;color:#78a300;left:492px;top:175px;"},{"id":"9024","flow_id":"1660","node_name":"\u65b0\u5efa\u6b65\u9aa4","node_to":"","icon":"icon-star","style":"width:120px;height:30px;line-height:30px;color:#0e76a8;left:453px;top:427px;"},{"id":"9025","flow_id":"1660","node_name":"\u65b0\u5efa\u6b65\u9aa4","node_to":"9023,9026","icon":"icon-heart","style":"width:120px;height:30px;line-height:30px;color:#f70;left:871px;top:219px;"},{"id":"9026","flow_id":"1660","node_name":"\u65b0\u5efa\u6b65\u9aa4","node_to":"","icon":"icon-star","style":"width:120px;height:30px;line-height:30px;color:#0e76a8;left:865px;top:328px;"},{"id":"9028","flow_id":"1660","node_name":"\u65b0\u5efa\u6b65\u9aa4","node_to":"","icon":"icon-star","style":"width:120px;height:30px;line-height:30px;color:#0e76a8;left:201px;top:244px;"},{"id":"9033","flow_id":"1660","node_name":"\u65b0\u5efa\u6b65\u9aa4","node_to":"","icon":"icon-star","style":"width:120px;height:30px;line-height:30px;color:#0e76a8;left:572px;top:427px;"},{"id":"9036","flow_id":"1660","node_name":"\u65b0\u5efa\u6b65\u9aa4","node_to":"","icon":"icon-star","style":"width:120px;height:30px;line-height:30px;color:#0e76a8;left:1105px;top:162px;"},{"id":"9037","flow_id":"1660","node_name":"\u65b0\u5efa\u6b65\u9aa4","node_to":"","icon":"icon-star","style":"width:120px;height:30px;line-height:30px;color:#0e76a8;left:235px;top:109px;"},{"id":"9038","flow_id":"1660","node_name":"\u65b0\u5efa\u6b65\u9aa4","node_to":"","icon":"icon-star","style":"width:120px;height:30px;line-height:30px;color:#0e76a8;left:460px;top:91px;"}]}

            $node=SysFlowNode::create([
                'flow_id'=>$flow->id,
                'node_title'=>'新建步骤',
                'style'=>'width:30px;height:30px;line-height:30px;color:#78a300;left:'.$data['left'].';top:'.$data['top'].';',
                'position_left'=>$data['left'],
                'position_top'=>$data['top']
            ]);

            //流程缓存流程图，方便视图直接使用
            if($flow->jsplumb==''){
                //第一次新建
                $jsplumb=[
                    'total'=>1,
                    "list"=>[],
                ];
            }else{
                //更新
                $jsplumb=json_decode($flow->jsplumb,true);
            }

            $jsplumb['list'][]=[
                'id'=>$node->id,
                'flow_id'=>$flow->id,
                'node_name'=>$node->node_title,
                'node_to'=>'',
                'icon'=>'',
                'style'=>$node->style
            ];

            $flow->jsplumb=json_encode($jsplumb, JSON_UNESCAPED_UNICODE);
            $flow->is_publish=0;
            $flow->save();

            $data=[
                'id'=>$node->id,
                'flow_id'=>$flow->id,
                'node_name'=>$node->node_title,
                'node_to'=>'',
                'icon'=>'',
                'style'=>'left:'.$data['left'].';top:'.$data['top'].';color:#0e76a8;'
            ];
            DB::commit();
            return $this->ajax_return('200', '操作成功！', $data);
        }catch(\Exception $e){
            DB::rollback();
            return ['status_code'=>-1,'message'=>$e->getMessage()];
        }

    }

    /**
     * attribute 属性集合
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function attribute(Request $request){
        $id=$request->input('id',0);
        $node=sysFlowNode::findOrFail($id);

        //当前步骤的下一步操作
        $next_node=SysFlowLink::where(['node_id'=>$node->id,'flow_id'=>$node->flow_id,'type'=>'Condition'])->get();
        $beixuan_node=SysFlowLink::where(['flow_id'=>$node->flow_id,'type'=>'Condition'])->where('node_id','<>',$node->id)->whereNotIn('node_id',$next_node->pluck('next_node_id'))->get();

        //流程模板 表单字段
        $flow=SysFlow::findOrFail($node->flow_id);
        $fields=$flow->template?$flow->template->template_form:[];

        //当前选择员工
        $select_emps=SysAdmin::whereIn('id',explode(',',SysFlowLink::where('type','Emp')->where('node_id',$node->id)->value('auditor')))->get();

        $select_depts=Dept::whereIn('id',explode(',',SysFlowLink::where('type','Dept')->where('node_id',$node->id)->value('auditor')))->get();
        
        $select_roles=SysRole::whereIn('id',explode(',',SysFlowLink::where('type','Role')->where('node_id',$node->id)->value('auditor')))->get();

        $sys=SysFlowLink::where(['node_id'=>$node->id,'flow_id'=>$node->flow_id,'type'=>'Sys'])->value('auditor');

        $flows=SysFlow::where('is_publish',1)->where('id','<>',$node->flow_id)->get();

        $nodes=sysFlowNode::where('flow_id',$node->flow_id)->get();

        $can_child=SysFlowLink::where(['node_id'=>$node->id,"type"=>"Condition"])->count()==1;

        return view('Admin.Base.Flow.partials.attribute')->with(compact('node','next_node','beixuan_node','fields','select_emps','sys','select_depts','select_roles','flows','nodes','can_child'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
            $all=$request->all();

            DB::beginTransaction();
            //更新当前步骤的基本信息以及样式

            $data = $this->get_params($request, ['node_title', 'style_color', 'icon', 'style_width', 'style_height', 'position', 'child_id', 'child_after', 'child_back_node']);

            $node=SYsFlowNode::findOrFail($id);

            //节点类型判断 0起始节点 1正常节点 2转入子流程节点
            if(in_array($data['position'], [9])){
                if(SysFlowLink::where('node_id',$id)->where("type","Condition")->count()>1){
                    return $this->ajax_return(500, '该节点是分支节点，不能设置为结束或起始步骤');
                }
            }

            //只能有一个起始节点为0
            if(in_array($data['position'], [0])){
                SysFlowNode::where(['flow_id'=>$node->flow_id,'position'=>0])->update([
                    'position'=>1
                ]);

                SysFlowNode::where(['flow_id'=>$node->flow_id,'id'=>$id])->update([
                    'position'=>0
                ]);
            }

            $data['style'] = 'width:'.$data['style_width'].'px;height:'.$data['style_height'].'px;line-height:30px;color:'.$data['style_color'].';left:'.$node->position_left.';top:'.$node->position_top.';';
            $node->update($data);

            // 同步更新jsplumb json数据
            $flow=SysFlow::findOrFail($node->flow_id);
            $jsplumb=json_decode($flow->jsplumb,true);

            foreach($jsplumb['list'] as $k=>$v){
                if($v['id']==$id){
                    $jsplumb['list'][$k]['node_name']=$node->node_title;
                    $jsplumb['list'][$k]['style']=$node->style;
                    $jsplumb['list'][$k]['icon']=$node->icon;
                }
            }

            $flow->jsplumb=json_encode($jsplumb, JSON_UNESCAPED_UNICODE);
            $flow->is_publish=0;
            $flow->save();

            //更新步骤 流转条件 node_condition
            $node_condition=explode(',', trim($request->input('node_condition',',')));

            foreach($node_condition as $v){
                //获取流转设置的表达式'$day' > '3'  AND '$day' <= '14'
                if($exp=$request->input('node_in_set_'.$v,'')){
                    //匹配变量
                    // $exp='$day > 3  AND $day <= 14';
                    preg_match_all("/\\$(\w+)/", $exp, $variables);

                    // dd($variables);
                    
                    if(empty($variables) && empty($variables[1])){
                        throw new \Exception("非法参数", 1);
                    }

                    $fields=$flow->template->template_form->pluck('field')->toArray();
                    
                    foreach($variables[1] as $var){

                        if(!in_array($var, $fields)){
                            throw new \Exception("非法参数", 1);
                        }

                        if(!NodeConf::where(['expression_field'=>$var,'node_id'=>$id])->first()){
                            NodeConf::create([
                                'node_id'=>$id,
                                'flow_id'=>$flow->id,
                                'expression_field'=>$var
                            ]);
                        }
                    }

                    //当前流转
                    $link=SysFlowLink::where(['flow_id'=>$flow->id,'node_id'=>$id,'next_node_id'=>$v])->firstOrFail();

                    $exp=str_replace(PHP_EOL," ",str_replace("'", "", $exp));
                    $link->update([
                        'expression'=>$exp
                    ]);
                }
            }

            //权限处理
            if($request->auto_person != 0){
                //系统自动选人
                if($flowlink=SysFlowLink::where(['flow_id'=>$flow->id,'type'=>'Sys','node_id'=>$id])->first()){
                    $flowlink->update([
                        'auditor'=>$request->auto_person
                    ]);
                }else{
                    SysFlowLink::create([
                        'flow_id'=>$flow->id,
                        'type'=>'Sys',
                        'node_id'=>$id,
                        'auditor'=>$request->auto_person,
                        'next_node_id'=>0,
                        'sort'=>100
                    ]);
                }

                SysFlowLink::where(['flow_id'=>$flow->id,'node_id'=>$id])->where('type','!=','Condition')->where('type','!=','Sys')->delete();
                
            }else{
                //指定角色
                if($role_ids=$all['range_role_ids']){
                    if($flowlink=SysFlowLink::where(['flow_id'=>$flow->id,'type'=>'Role','node_id'=>$id])->first()){
                        $flowlink->update([
                            'auditor'=>$role_ids
                        ]);
                    }else{
                        SysFlowLink::create([
                            'flow_id'=>$flow->id,
                            'type'=>'Role',
                            'node_id'=>$id,
                            'auditor'=>$role_ids,
                            'next_node_id'=>0,
                            'sort'=>100
                        ]);
                    }
                }else{
                    SysFlowLink::where(['flow_id'=>$flow->id,'node_id'=>$id])->where('type','Role')->delete();
                }

                //指定部门
                if($dept_ids=$all['range_dept_ids']){
                    if($flowlink=SysFlowLink::where(['flow_id'=>$flow->id,'type'=>'Dept','node_id'=>$id])->first()){
                        $flowlink->update([
                            'auditor'=>$dept_ids
                        ]);
                    }else{
                        SysFlowLink::create([
                            'flow_id'=>$flow->id,
                            'type'=>'Dept',
                            'node_id'=>$id,
                            'auditor'=>$dept_ids,
                            'next_node_id'=>0,
                            'sort'=>100
                        ]);
                    }  
                }else{
                    SysFlowLink::where(['flow_id'=>$flow->id,'node_id'=>$id])->where('type','Dept')->delete();
                }

                //指定员工
                if($emp_ids=$all['range_emp_ids']){
                    if($flowlink=SysFlowLink::where(['flow_id'=>$flow->id,'type'=>'Emp','node_id'=>$id])->first()){
                        $flowlink->update([
                            'auditor'=>$emp_ids
                        ]);
                    }else{
                        SysFlowLink::create([
                            'flow_id'=>$flow->id,
                            'type'=>'Emp',
                            'node_id'=>$id,
                            'auditor'=>$emp_ids,
                            'next_node_id'=>0,
                            'sort'=>100
                        ]);
                    }
                }else{
                    SysFlowLink::where(['flow_id'=>$flow->id,'node_id'=>$id])->where('type','Emp')->delete();
                }

                SysFlowLink::where(['flow_id'=>$flow->id,'node_id'=>$id])->where('type','!=','Condition')->where('type', 'Sys')->delete();
            }            

            DB::commit();
            return redirect()->back();
        }catch(\Exception $e){
            DB::rollback();
            dd($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $data=$request->all();

            $flow=SysFlow::findOrFail($data['flow_id']);

            //删除流程连线
            SysFlowLink::where(['flow_id'=>$data['flow_id'],'node_id'=>$id])->delete();

            //更新引用节点
            SysFlowLink::where(['flow_id'=>$data['flow_id'],'next_node_id'=>$id])->update([
                'next_node_id'=>-1
            ]);

            //删除节点
            $node=SysFlowNode::where(['flow_id'=>$data['flow_id']])->findOrFail($id);
            $node->delete();

            //修改缓存流程图
            $jsplumb=json_decode($flow->jsplumb,true);
            foreach($jsplumb['list'] as $k=>$v){
                if($v['id']==$id){
                    unset($jsplumb['list'][$k]);
                }
            }
            $flow->jsplumb=json_encode($jsplumb ,JSON_UNESCAPED_UNICODE);
            $flow->is_publish=0;
            $flow->save();

            DB::commit();
            $this->log(__CLASS__, __FUNCTION__, $request, "删除 流程节点");
            return $this->ajax_return(200, '删除成功');
        }catch(\Exception $e){
            DB::rollback();
            return redirect()->back()->with(['status_code'=>-1,'message'=>$e->getMessage()]);
        }
    }

    /**
     * 查找条件
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function condition(Request $request){
        $flow_id=$request->input('flow_id');
        $node_id=$request->input('node_id');
        $next_node_id=$request->input('next_node_id');

        //当前流转
        $flowlink=SysFlowLink::where(['node_id'=>$node_id,'next_node_id'=>$next_node_id,'flow_id'=>$flow_id,'type'=>'Condition'])->firstOrFail();
        
        $data=[];

        $fields=SysFlow::findOrFail($flow_id)->template->template_form;
        $expression=str_replace($fields->pluck('field')->toArray(), $fields->pluck('field_name')->toArray(), str_replace('$','',$flowlink->expression));
        
        $data[$flowlink->next_node_id]=[
            'desc'=>$expression,
            'option'=>''
        ];

        return response()->json($data);
    }

    /**
     * 修改为发起节点
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function setFirst(Request $request){
        $flow_id=$request->input('flow_id',0);
        $node_id=$request->input('node_id',0);

        //
        if(SysFlowLink::where('node_id',$node_id)->where("type","Condition")->where('next_node_id','>','-1')->count() > 1){
            return $this->ajax_return(500, '该节点是分支节点，不能设置为初始步骤！');
        }

        SysFlowNode::where(['flow_id'=>$flow_id,'position'=>0])->update([
            'position'=>1
        ]);

        SysFlowNode::where(['flow_id'=>$flow_id,'id'=>$node_id])->update([
            'position'=>0
        ]);

        return $this->ajax_return(200, 'success!');
    }

    /**
     * 修改为结束节点
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function setLast(Request $request){
        $flow_id=$request->input('flow_id',0);
        $node_id=$request->input('node_id',0);

        if(Flowlink::where('node_id',$node_id)->where("type","Condition")->where('next_node_id','>','-1')->count()>1){
            return $this->ajax_return(500, '该节点是分支节点，不能设置为结束步骤！');
        }

        SysFlowNode::where(['flow_id'=>$flow_id,'position'=>0])->update([
            'position'=>1
        ]);

        SysFlowNode::where(['flow_id'=>$flow_id,'id'=>$node_id])->update([
            'position'=>9
        ]);

        return $this->ajax_return(200, 'success!');
    }
}
