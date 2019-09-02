<?php

namespace App\Http\Controllers\Admin\Rpa;

use App\Models\Admin\Admin\SysAdmin;
use App\Models\Admin\Rpa\rpa_immedtasks;
use Illuminate\Http\Request;
use App\Models\Admin\Rpa\rpa_releasetasks;
use App\Http\Controllers\admin\rpa\ImmedtaskController;
use App\Http\Controllers\Base\BaseAdminController;

/**
 * JJRVisController
 * @author hsu lay
 */
class JJRVisController extends BaseAdminController
{
    //task name
    private $task_name;

    /**
     * __CONSTRUCT
     */
    public function __CONSTRUCT()
    {
        $this->task_name = 'MediatorVisit';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "居间人回访分配 任务列表");
        return view('admin.rpa.JJRVis.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //获取客服部名单
        $conditions = [["groupID","=",2]];
        $result = SysAdmin::where($conditions)->get();
        return view('admin.rpa.JJRVis.add', ['admin' => $result]);
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
        $data['jsondata'] = json_encode(['namelist' => isset($data['jsondata']) ? implode(',',$data['jsondata']) :'']);
        //创建任务
        $tid = rpa_releasetasks::create($data);
        //发布任务
        $this->immedtask($data['name']);
        $this->log(__CLASS__, __FUNCTION__, $request, "添加 居间人回访分配 任务");
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
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 居间人回访分配 参数");
        return view('admin.rpa.JJRVis.show', ['info' => $info]);
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
        $info['data'] = explode(',', json_decode($info['jsondata'],true)['namelist']);
        //获取客服部名单
        $conditions = [["groupID","=",2]];
        $result = SysAdmin::where($conditions)->get();

        $this->log(__CLASS__, __FUNCTION__, $request, "查看 居间人回访分配 参数");
        return view('admin.rpa.JJRVis.edit', ['info' => $info,'admin'=>$result]);
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
        $data['jsondata'] = json_encode(['namelist' => isset($data['jsondata']) ? implode(',',$data['jsondata']) :'']);
        //更新任务
        $tid = rpa_releasetasks::where('id',$id)->update($data);
        //发布任务
        $this->immedtask($data['name']);
        $this->log(__CLASS__, __FUNCTION__, $request, "修改 居间人回访分配 任务");
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
        $this->log(__CLASS__, __FUNCTION__, $request, "删除 居间人回访分配 任务");
        return $this->ajax_return(200, '操作成功！');
    }

    /**
     * pagenation
     */
    public function pagenation(Request $request){
        $rows = $request->rows;
        $conditions = [['name','=','MediatorVisit']];
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
        $this->log(__CLASS__, __FUNCTION__, $request, "立即发布 居间人回访分配 页");
        $jsondata = "";
        if($request->id){
            $res = rpa_releasetasks::find($request->id);
            $jsondata = explode(',',json_decode($res->jsondata,true)['namelist']);
        }
        //获取客服部名单
        $conditions = [["groupID","=",2]];
        $result = SysAdmin::where($conditions)->get();

        return view('admin/rpa/JJRVis/add_immed',['jsondata'=>$jsondata,'admin'=>$result]);
    }
    /**
     * 发布立即任务
     */
    public function insertImmedtasks(Request $request){
        $task = $this->get_params($request, ['description','jsondata']);
        $task['name'] = 'MediatorVisit';
        $data = ['name'=>$task['name'],'jsondata'=>$task['jsondata'],'description'=>$task['description']];
        $data['jsondata'] = json_encode(['namelist' => isset($data['jsondata']) ? implode(',',$data['jsondata']) :''],JSON_UNESCAPED_UNICODE);
        $this->log(__CLASS__, __FUNCTION__, $request, "立即发布 {$task['name']} 任务");
        rpa_immedtasks::create($data);
        return $this->ajax_return(200, '操作成功！');
    }
}
