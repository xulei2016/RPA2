<?php

namespace App\Http\Controllers\Admin\Base\Flow;

use Illuminate\Http\Request;
use App\Models\Admin\Base\Flow\SysFlow;
use App\Models\Admin\Base\Flow\SysFlowGroup;
use App\Http\Controllers\Base\BaseAdminController;

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
        return view($this->flow.'add', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->get_params($request, ['title', 'flow_no', 'groupID', 'sort', 'description'], false);
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
        return view($this->flow.'edit', compact('groups', 'data'));
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
        $data = $this->get_params($request, ['title', 'flow_no', 'groupID', 'sort', 'description']);
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
        SysFlow::destroy($id);
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
}
