<?php

namespace App\Http\Controllers\Admin\Rpa;

use App\Models\Admin\Rpa\rpa_immedtasks;
use Illuminate\Http\Request;
use App\Models\Admin\Rpa\rpa_releasetasks;
use App\Http\Controllers\admin\rpa\ImmedtaskController;
use App\Http\Controllers\Base\BaseAdminController;

/**
 * OabRemindingController
 * @author hsu lay
 */
class OabremindingController extends BaseAdminController
{
    //task name
    private $task_name;

    /**
     * __CONSTRUCT
     */
    public function __CONSTRUCT()
    {
        $this->task_name = 'OABReminding';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "客户资金查询任务 任务列表");
        return view('admin.rpa.OabReminding.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.rpa.OabReminding.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->get_params($request, ['week','date','description','time','jsondata','start_time','end_time','mins',['implement_type', 0]]);
        $data['name'] = $this->task_name;
        $data['week'] = isset($data['week']) ? implode(',',$data['week']) :'';
        $time = $this->get_params($request, ['start_time','end_time','mins'],false);
        $data['time'] = $data['time'] ?? $this::slice_time($time) ;
        //创建任务
        $tid = rpa_releasetasks::create($data);
        //发布任务
        $this->immedtask($data['name']);
        $this->log(__CLASS__, __FUNCTION__, $request, "添加 客户资金查询任务 任务");
        return $this->ajax_return(200, '操作成功！');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $info = rpa_releasetasks::find($id);
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 客户资金查询任务 参数");
        return view('admin.rpa.OabReminding.show', ['info' => $info]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $info = rpa_releasetasks::find($id);
        $info['week'] = isset($info['week']) ? explode(',',$info['week']) :'';
        $info['data'] = isset($info['jsondata']) ? json_decode($info['jsondata'], false) :'';
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 客户资金查询任务 参数");
        return view('admin.rpa.OabReminding.edit', ['info' => $info]);
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
        $data = $this->get_params($request, [['week',null],['date',null],'description','time','jsondata','start_time','end_time','mins',['implement_type', 0]], false);
        $data['name'] = $this->task_name;
        $data['week'] = isset($data['week']) ? implode(',',$data['week']) :'';

        $time = $this->get_params($request,['start_time','end_time','mins']);
        $data['time'] = $data['time'] ?? $this::slice_time($time) ;
        //更新任务
        $tid = rpa_releasetasks::where('id',$id)->update($data);
        //发布任务
        $this->immedtask($data['name']);
        $this->log(__CLASS__, __FUNCTION__, $request, "修改 客户资金查询任务 任务");
        return $this->ajax_return(200, '操作成功！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $ids)
    {
        $ids = explode(',', $ids);
        $result = rpa_releasetasks::destroy($ids);
        $this->log(__CLASS__, __FUNCTION__, $request, "删除 客户资金查询任务 任务");
        return $this->ajax_return(200, '操作成功！');
    }
    
    /**
     * pagenation
     */
    public function pagenation(Request $request){
        $rows = $request->rows;
        $conditions = [['name','=','OABReminding']];
        $result = rpa_releasetasks::where($conditions)->paginate($rows);
        return $result;
    }

    /**
     * immedtask
     */
    public function immedtask($name){
        $immedtask = new ImmedtaskController;
        $immedtask->create($name);
    }

    /***********************************立即任务*********************************************/
    /**
     * 立即任务
     */
    public function immedtasks(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "立即发布 客户资金查询任务 页");
        $jsondata = "";
        if($request->id){
            $res = rpa_releasetasks::find($request->id);
            $jsondata = json_decode($res->jsondata,true);
        }
        return view('admin/rpa/OabReminding/add_immed',['jsondata'=>$jsondata]);
    }
    /**
     * 发布立即任务
     */
    public function insertImmedtasks(Request $request){
        $task = $this->get_params($request, ['description','jsondata']);
        $task['name'] = 'OABReminding';
        $data = ['name'=>$task['name'],'jsondata'=>$task['jsondata'],'description'=>$task['description']];
        $this->log(__CLASS__, __FUNCTION__, $request, "立即发布 {$task['name']} 任务");
        rpa_immedtasks::create($data);
        return $this->ajax_return(200, '操作成功！');
    }
}
