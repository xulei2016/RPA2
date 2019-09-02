<?php

namespace App\Http\Controllers\Admin\Base\CallCenter;

use App\Events\CallCenterCustomerEvent;
use App\Events\CallCenterManagerEvent;
use App\Models\Admin\Admin\SysAdmin;
use App\Models\Admin\Admin\SysAdminGroup;
use App\Models\Admin\Base\CallCenter\SysManager;
use App\Models\Admin\Base\CallCenter\SysRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Maatwebsite\Excel\Facades\Excel;

class ManagerController extends BaseController
{
    private $view_prefix = 'admin.base.callCenter.manager.';

    /**
     * 列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view($this->view_prefix.'index');
    }

    /**
     * 新增界面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function create(){
        $groups = SysAdminGroup::get()->toArray();
        $admins = SysAdmin::get()->toArray();
        return view($this->view_prefix.'add',  ['groups' => $groups, 'admins' => $admins]);
    }

    /**
     * 新增数据
     * @param Request $request
     * @return array
     */
    public function store(Request $request){
        $data = $this->get_params($request, ['nickname','group_id','sys_admin_id','label','work_number','desc']);
        $manager = SysManager::where('sys_admin_id', $data['sys_admin_id'])->first();
        if($manager) return $this->ajax_return(500, '改用户已存在');
        SysManager::create($data);
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * 编辑界面
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id){
        $groups = SysAdminGroup::get()->toArray();
        $manager = SysManager::where('id', $id)->first();
        $admin = SysAdmin::where('id', $manager->sys_admin_id)->first();
        return view($this->view_prefix.'edit', ['groups' => $groups, 'manager' => $manager,'admin'=>$admin]);
    }

    /**
     * 更新数据
     * @param Request $request
     * @param $id
     * @return array
     */
    public function update(Request $request, $id){
        $data = $this->get_params($request, ['nickname','group_id','sys_admin_id','label','work_number','desc']);
        SysManager::where('id', $id)->update($data);
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * 删除
     * @param Request $request
     * @param $ids
     * @return array
     */
    public function destroy(Request $request, $ids){
        $ids = explode(',', $ids);
        SysManager::destroy($ids);
        return $this->ajax_return('200', '操作成功');
    }

    /**
     * 分页列表
     * @param Request $request
     * @return mixed
     */
    public function pagination(Request $request){
        $selectInfo = $this->get_params($request, ['nickname','work_number']);
        $condition = $this->getPagingList($selectInfo, ['nickname'=>'like', 'work_number'=>'=']);
        $rows = $request->rows;
        $order = ($request->sort ?? 'id');
        $sort = $request->sortOrder ?? 'desc';
        $result = SysManager::from('sys_call_center_managers as sm')
            ->where($condition)
            ->leftJoin('sys_admin_groups as sg', 'sm.group_id', '=', 'sg.id')
            ->leftJoin('sys_admins as sa', 'sm.sys_admin_id', '=', 'sa.id')
            ->select(['sm.*', 'sg.group','sa.realName'])
            ->orderBy($order, $sort)
            ->paginate($rows);
        return $result;
    }

    /**
     * 暂时用不到
     */
    public function show(){}

    /**
     * 导出
     * @param Request $request
     */
    public function export(Request $request){
        $param = $this->get_params($request, ['nickname', 'work_number']);
        $conditions = $this->getPagingList($param, ['nickname'=>'like', 'work_number' => '=']);
        $modelBuild = SysManager::from('sys_call_center_managers as sm')
            ->where($conditions)
            ->leftJoin('sys_admin_groups as sg', 'sm.group_id', '=', 'sg.id')
            ->leftJoin('sys_admins as sa', 'sm.sys_admin_id', '=', 'sa.id')
            ->select(['sm.nickname','sm.work_number','sm.label', 'sg.group','sa.realName']);
        if($request->has('id')){
            $data = $modelBuild->whereIn('sm.id', explode(',',$request->get('id')))->get()->toArray();
        }else{
            $data = $modelBuild->get()->toArray();
        }
        $cellData = [];
        $cellData[] = array_keys($data[0]);
        foreach($data as $k => $info){
            array_push($cellData, array_values($info));
        }
        Excel::create('客服人员信息',function($excel) use ($cellData){
            $excel->sheet('客服人员信息', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }

    /**
     * 客服发送信息
     * @param Request $request
     */
    public function sendByManager(Request $request){
        $customer_id = $request->post('customer_id');
        $manager_id = $request->post('manager_id');
        $content = $request->post('content');
        $type = $request->post('type'); //类型
        $data = $this->messagePackaging($customer_id, $manager_id, 'manager','customer', $content, $type);
        $record_detail = $request->post();
        $customer = Redis::hGet(self::ONLINE_CUSTOMER_LIST, $customer_id);
        $customer = json_decode($customer, true);
        $record_detail['record_id'] = $customer['record_id'];
        $this->storeMessage($record_detail);
        broadcast(new CallCenterCustomerEvent($data));
        return $this->ajax_return(200, 'success');
    }

    /**
     * 客服主动连接
     * @param Request $request
     */
    public function connect(Request $request){
        $customer_id = $request->post('customer_id');
        $manager_id = $request->post('manager_id');
        $record_id = $request->post('record_id');
        $customer = Redis::hGet(self::ONLINE_CUSTOMER_LIST, $customer_id);
        if(!$customer) return $this->ajax_return(500, '该用户可能不在线');
        $customer = json_decode($customer, true);
        if(isset($customer['manager_id']) && $customer['manager_id'] == $manager_id) return $this->ajax_return(200,'success');
        if($customer['status'] != 1) return $this->ajax_return(500, '该用户无法连接');
        $customer['manager_id'] = $manager_id;
        $customer['status'] = 2;
        SysRecord::where('id', $record_id)->update(['manager_id' => $manager_id]);
        $this->customer_id = $customer_id;
        Redis::hSet(self::ONLINE_CUSTOMER_LIST, $customer_id, json_encode($customer));
        $this->noticeOnlineManager('customer_change');
        $this->noticeOnlineCustomer($manager_id, 'manager_connect');
        return $this->ajax_return(200, 'success');
    }

    /**
     * 接入客服更换
     * @todo 登录用户manager_id
     */
    public function transfer(Request $request){
        $this->customer_id = $request->post('customer_id');
        $manager_id = $request->post('manager_id');
        $old_manager_id = auth()->guard()->user()->id; //来源应该是登录用户
        $customer = Redis::hGet(self::ONLINE_CUSTOMER_LIST, $this->customer_id);
        $customer = json_decode($customer, true);
        if($customer['manager_id'] != $old_manager_id) return $this->ajax_return(500, "你无权将用户转移出去");
        $manager = Redis::hGet(self::ONLINE_MANAGER_LIST, $manager_id);
        $manager = json_decode($manager, true);
        //从新存入消息记录主表, 返回id
        $record = SysRecord::create(['customer_id' => $this->customer_id, 'manager_id' => $manager_id]);
        $customer['record_id'] = $record->id;
        $manager['record_id'] = $record->id;
        $customer_data = $this->packaging(self::CATEGORY_EVENT, $this->customer_id, $manager_id, "system", 'customer', $manager, 'manager_change');        
        broadcast(new CallCenterCustomerEvent($customer_data));
        $customer['manager_id'] = $manager_id;
        Redis::hSet(self::ONLINE_CUSTOMER_LIST, $this->customer_id, json_encode($customer));
        $manager_data = $this->managerPackaging(self::CATEGORY_EVENT, $old_manager_id, $manager_id, 'manager_change', $this->customer_id);
        broadcast(new CallCenterManagerEvent($manager_data));
        return $this->ajax_return(200, '操作成功');
    }

    /**
     * 获取在线客服列表
     * @return array
     */
    public function getOnlineManagerList(){
        $list = Redis::hGetAll(self::ONLINE_MANAGER_LIST);
        if(!$list) $this->ajax_return(500, '暂无数据');
        $result = [];
        foreach ($list as $k => $v) {
            $manager = json_decode($v, true);
            if(isset($manager['status']) && $manager['status'] == 1) {
                $result[] = $manager;
            }
        }
        return $this->ajax_return(200, 'success', $result);
    }

    /**
     * 客服离开
     * @param Request $request
     */
    public function leave(Request $request) {
        $manager_id = $request->post('manager_id');
        $this->leaveOnlineManagerList($manager_id);
        return $this->ajax_return(200, 'success');
    }

    /**
     * @param Request $request
     */
    public function leaveOnlineManagerList($manager_id){
        $data = Redis::hGet(self::ONLINE_MANAGER_LIST, $manager_id);
        $this->noticeManager('manager_remove', json_decode($data, true));
        Redis::hDel(self::ONLINE_MANAGER_LIST, $manager_id);
    }

    /**
     * 客服修改单个信息并通知客户
     * @param Request $request
     */
    public function updateOne(Request $request){
        $data = $this->get_params($request, ['id','key','value']);
        SysManager::where('id', $data['id'])->update([
            $data['key'] => $data['value']
        ]);
        $manager = Redis::hGet(self::ONLINE_MANAGER_LIST, $data['id']);
        $manager = json_decode($manager, true);
        $manager[$data['key']] = $data['value'];
        Redis::hSet(self::ONLINE_MANAGER_LIST, $data['id'], json_encode($manager));
//        $this->noticeOnlineCustomer($manager['id'], 'manager_change');
        return $this->ajax_return(200, 'success');

    }

    /**
     * 通知客户 客服信息发生变化
     * @param $manager_id
     * @param $type
     */
    public function noticeOnlineCustomer($manager_id, $type)
    {
        $manager = Redis::hGet(self::ONLINE_MANAGER_LIST, $manager_id);
        $manager = json_decode($manager, true);
        $customer_data = $this->packaging(self::CATEGORY_EVENT, $this->customer_id, $manager_id, "system", 'customer', $manager, $type);
        broadcast(new CallCenterCustomerEvent($customer_data));
    }
}