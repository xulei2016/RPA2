<?php

namespace App\Http\Controllers\Admin\Base\Flow;

use Illuminate\Http\Request;
use App\Models\Admin\Base\Flow\SysFlow;
use App\Http\Controllers\Base\BaseAdminController;

class FlowController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Admin.Base.Flow.flowList');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Admin.Base.Flow.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->get_params($request, ['title', 'groupID', 'sort', 'description']);
        $result = SysFlow::create($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "添加 流程");
        return $this->ajax_return('200', '操作成功！');
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
        //
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

    /**
     * pagenation list
     */
    public function pagenation(Request $request){
        $rows = $request->rows;
        $data = $this->get_params($request, ['name']);
        $conditions = $this->getPagingList($data, ['name'=>'like']);
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder;
        $result = SysAdmin::where($conditions)
            ->orderBy($order, $sort)
            ->paginate($rows);
        return $result;
    }
}
