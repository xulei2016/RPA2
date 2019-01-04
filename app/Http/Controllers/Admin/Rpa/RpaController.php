<?php

namespace App\Http\Controllers\Admin\Rpa;

use App\models\admin\rpa\rpa_maintenance;
use Illuminate\Http\Request;
use App\Http\Controllers\Base\BaseAdminController;
use App\Models\Admin\Rpa\rpa_timetasks;
use App\Models\Admin\Rpa\rpa_releasetasks;
use App\Models\Admin\Rpa\rpa_immedtasks;

/**
 * RpaController
 * @author hsu lay
 */
class RpaController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 rpa 任务");
        return view('admin.rpa.center.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "添加 rpa 任务页面");
        return view('admin.rpa.center.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->get_params($request, ['name','filepath','failtimes','timeout','isfp','bewrite','notice_type','noticeAccepter'], false);
        if(isset($data['noticeAccepter'])){
            $data['noticeAccepter'] = implode(',', $data['noticeAccepter']);
        }
        $result = rpa_maintenance::create($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "添加 任务");
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\admin\rpa\rpa_maintenance  $rpa_maintenance
     * @return \Illuminate\Http\Response
     */
    public function show(rpa_maintenance $rpa_maintenance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\models\admin\rpa\rpa_maintenance  $rpa_maintenance
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $task = rpa_maintenance::where('id','=',$id)->first();
        $task->noticeAccepter = explode(',', $task->noticeAccepter);
        $accepter = self::getAccepter($request, $task->notice_type);
        $this->log(__CLASS__, __FUNCTION__, $request, "修改 rpa 任务页面");
        return view('admin.rpa.center.edit', ['info' => $task, 'accepters' => $accepter]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\admin\rpa\rpa_maintenance  $rpa_maintenance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $this->get_params($request, ['name','filepath','failtimes','timeout','isfp','bewrite','notice_type','noticeAccepter']);
        if(isset($data['noticeAccepter'])){
            $data['noticeAccepter'] = implode(',', $data['noticeAccepter']);
        }
        $result = rpa_maintenance::where('id','=',$id)->update($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "修改 任务");
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\admin\rpa\rpa_maintenance  $rpa_maintenance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $ids)
    {
        $ids = explode(',', $ids);
        $result = rpa_maintenance::destroy($ids);
        $this->log(__CLASS__, __FUNCTION__, $request, "删除权限菜单");
        return $this->ajax_return(200, '操作成功！');
    }

    /**
     * pagenation
     */
    public function pagenation(Request $request){
        $rows = $request->rows;
        $data = $this->get_params($request, ['name','role','type']);
        $conditions = $this->getPagingList($data, ['name'=>'like', 'role'=>'=', 'type'=>'=']);
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder;
        $result = rpa_maintenance::where($conditions)
                ->orderBy($order, $sort)
                ->paginate($rows);
        return $result;
    }

    /**
     * getAccepter
     */
    public function getAccepter(Request $request, $send_info_type = ''){
        $param = ($send_info_type !== '') ? $send_info_type : $request->param ;
        $result = [];
        if($param){
            switch($param){
                case 1:
                    $result = self::getSingleAccepter();
                    break;
                case 2:
                    $result = self::getGroupAccepter();
                    break;
                case 3:
                    $result = self::getRoleAccepter();
                    break;
            }
        }
        if($send_info_type !== ''){
            return $result;
        }
        return $this->ajax_return('200', '操作成功！', $result);
    }

    //getSingleAccepter
    private function getSingleAccepter(){
        $model = new \App\Models\Admin\Admin\SysAdmin;
        $admin = $model->where([['id','!=','1'],['type','=','1']])->get(['id', 'realName as name']);
        return $admin;
    }

    //getGroupAccepter
    private function getGroupAccepter(){
        $model = new \App\Models\Admin\Admin\SysAdminGroup;
        $group = $model->get(['id', 'group as name']);
        return $group;
    }

    //getRoleAccepter
    private function getRoleAccepter(){
        $model = new \App\Models\Admin\Base\SysRole;
        $role = $model->where('type','=','1')->get(['id', 'desc as name']);
        return $role;
    }
    /***********************************************任务队列***************************************************/
    //queue list
    public function queue(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 任务队列");
        return view('admin/rpa/center/queue');
    }
    //RPA edit
    public function editQueue(Request $request){
        $id = $request->id;
        $info = rpa_timetasks::find($id);
        $info['data'] = json_decode($info['jsondata'],true);
        $this->log(__CLASS__, __FUNCTION__, $request, "修改 队列 页面");
        return view('admin/rpa/Center/editQueue',['info'=>$info]);
    }
    //RPA update
    public function updateQueue(Request $request){
        $data = $this->get_params($request, ['id','name','state','time','jsondata','tid']);
        $this->log(__CLASS__, __FUNCTION__, $request, "更新 队列 信息");
        $result = rpa_timetasks::where('id','=',$data['id'])->update($data);
        return $this->ajax_return('200', '操作成功！', $result);
    }

    //delete
    public function deleteQueue(Request $request){
        $ids = $request->ids;
        $ids = explode(',',$ids);
        $this->log(__CLASS__, __FUNCTION__, $request, "删除 队列");
        $result = rpa_timetasks::destroy($ids);
        return $this->ajax_return('200', '操作成功！', $result);
    }

    //pagenation
    public function queuePagination(Request $request){
        $rows = $request->rows;
        $data = $this->get_params($request, ['name','role','type']);
        $conditions = $this->getPagingList($data, ['name'=>'like', 'role'=>'=', 'type'=>'=']);
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder;
//        $sort = ['id','asc'];
        $result = rpa_timetasks::where($conditions)
            ->orderBy($order, $sort)
            ->paginate($rows);
        return $result;
    }
    /********************************发布任务一览*************************************************/
    //task list
    public function taskList(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 发布任务 总览");
        return view('admin/rpa/Center/taskList');
    }

    //pagenation
    public function taskPagination(Request $request){
        $rows = $request->rows;
        $data = $this->get_params($request, ['name','role','type']);
        $conditions = $this->getPagingList($data, ['name'=>'like', 'role'=>'=', 'type'=>'=']);
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder;
        $result = rpa_releasetasks::where($conditions)
            ->orderBy($order, $sort)
            ->paginate($rows);
        return $result;
    }

    //立即发布任务
    public function immedtasks(Request $request){
        $id = $request->id;
        $task = rpa_releasetasks::find($id);
        $data = ['name'=>$task['name'],'jsondata'=>$task['jsondata'],'tid'=>$task['id']];
        $this->log(__CLASS__, __FUNCTION__, $request, "立即发布 {$task['name']}-{$task['id']} 任务");
        $reslut = rpa_immedtasks::create($data);
        return $this->ajax_return('200', '操作成功！',$reslut);
    }
}
