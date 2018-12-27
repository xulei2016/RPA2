<?php

namespace App\Http\Controllers\Admin\Rpa;

use App\models\admin\rpa\rpa_maintenance;
use Illuminate\Http\Request;
use App\Http\Controllers\Base\BaseAdminController;

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
}
