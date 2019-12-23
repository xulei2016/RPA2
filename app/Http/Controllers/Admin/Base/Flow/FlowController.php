<?php

namespace App\Http\Controllers\Admin\Base\Flow;

use Illuminate\Http\Request;
use App\Models\Admin\Base\Flow\SysFlow;
use App\Models\Admin\Base\Flow\SysFlowLink;
use App\Models\Admin\Base\Flow\SysFlowGroup;
use App\Models\Admin\Base\Flow\SysFlowTemplate;
use App\Http\Controllers\Base\BaseAdminController;

/**
 * FlowController class
 *
 * @Description 流程中心
 * @author Hsu Lay
 * @since 20191126
 */
class FlowController extends BaseAdminController
{

    protected $flow = 'Admin.Base.Flow.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->flow.'flowList');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = SysFlowGroup::all();
        $temps = SysFlowTemplate::get();
        return view($this->flow.'add', compact('groups', 'temps'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->get_params($request, ['title', 'flow_no', 'groupID', 'sort', 'description', 'template_id'], false);
        $result = SysFlow::create($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "添加 流程");
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * design
     * 
     * @param
     */
    public function design($id){
        $flow=SysFlow::findOrFail($id);
        return view($this->flow.'design',compact('flow'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $data = SysFlow::findOrFail($id);
        // $groups = SysFlowGroup::all();
        // return view('Admin.Base.Flow.show', compact('groups', 'data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = SysFlow::findOrFail($id);
        $groups = SysFlowGroup::all();
        $temps = SysFlowTemplate::get();
        return view($this->flow.'edit', compact('groups', 'data', 'temps'));
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
        $data = $this->get_params($request, ['title', 'flow_no', 'groupID', 'sort', 'description', 'template_id']);
        $result = SysFlow::where('id', $id)->update($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "修改 流程");
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $flow=SysFlow::findOrFail($id);

        if(Entry::where('flow_id',$flow->id)->first()){
            return $this->ajax_return('500', '该流程已经被使用，不能删除！');
        }

        if(\Models\Admin\Base\Flow\SysFlowNode::where('child_flow_id',$flow->id)->first()){
            return $this->ajax_return('500', '该流程已经被使用，不能删除！');
        }

        $flow->getNodes()->delete();
        $flow->process_var()->delete();
        $flow->delete();

        $this->log(__CLASS__, __FUNCTION__, $request, "删除 流程");
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * pagenation list
     */
    public function pagenation(Request $request){
        $rows = $request->rows;
        $data = $this->get_params($request, ['title']);
        $conditions = $this->getPagingList($data, ['title'=>'like']);
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder;
        $result = SysFlow::where($conditions)
            ->leftJoin('sys_flow_groups', 'sys_flows.groupID', '=', 'sys_flow_groups.id')
            ->select(['sys_flows.*', 'sys_flow_groups.name'])
            ->orderBy($order, $sort)
            ->paginate($rows);
        return $result;
    }

    /**
     * publish function
     *
     * @param Request $request
     * @return void
     * @Description 发布
     */
    public function publish(Request $request)
    {
        try{

            $flow_id=$request->input('flow_id',0);
            $flow=SysFlow::findOrFail($flow_id);

            //流程最短步骤数检测
            if(SysFlowLink::where(['flow_id'=>$flow->id,'type'=>'Condition'])->count() <= 1){
                return $this->ajax_return(500, '发布失败，至少两个步骤');
            }

            //是否有无效节点
            if(SysFlowLink::where(['flow_id'=>$flow->id,'type'=>'Condition','next_node_id'=>-1])->count() > 1){
                return $this->ajax_return(500, '发布失败，有步骤没有连线');
            }

            //确定起始步骤
            if(!SysFlowLink::whereHas('node',function($query){
                $query->where('position',0);
            })->where('flow_id',$flow_id)->first()){
                return $this->ajax_return(500, '发布失败，请设置起始步骤');
            }

            // if(!Flowlink::whereHas('process',function($query){
            //     $query->where('position',9);
            // })->first()){
            //     return response()->json([
            //         'status_code'=>1,
            //         'message'=>'发布失败，请设置结束步骤'
            //     ]);
            // }

            //是否指定审批对象
            $flowlinks=SysFlowLink::where(['flow_id'=>$flow->id,'type'=>'Condition'])->whereHas('node',function($query){
                $query->where('position','!=',0);
            })->get();
            foreach($flowlinks as $v){
                if(!SysFlowLink::where(['flow_id'=>$flow->id,'node_id'=>$v->node_id])->where('type','!=','Condition')->whereHas('node',function($query){
                    $query->where('position','!=',0);
                })->first()){
                    return $this->ajax_return(500, '发布失败，请给设置步骤审批权限');
                }
            }
            $flow->is_publish=1;
            $flow->save();

            $this->log(__CLASS__, __FUNCTION__, $request, "删除 流程");
            return $this->ajax_return(200, '发布成功！');
        }catch(\Exception $e){
            return redirect()->back()->with(['status_code'=>1,'message'=>$e->getMessage()]);
        }
    }

}
