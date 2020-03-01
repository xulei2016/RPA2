<?php

namespace App\Http\Controllers\Admin\Base\Sms;

use App\Http\Controllers\base\BaseAdminController;
use App\Models\Admin\Base\Sys\SMS\SysSmsSetting;
use App\Models\Admin\Base\Sys\SMS\SysSmsLog;
use App\Models\Admin\Base\Sys\SMS\SysSmsGateway;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

use SMS;

/**
 * Class SmsController
 *
 * @author hsu lay
 * @package App\Http\Controllers\Admin\Base\Sms
 */
class SmsController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "短信发送 列表");
        return view('admin/Base/sms/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $gateways = SysSmsSetting::where('status', 1)->get();
        return view('admin/Base/sms/create', compact('gateways'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $data = $this->get_params($request, ['name', 'unique_name', 'available_list', ['status', 0], 'desc']);
        $data['available_list'] = isset($data['available_list']) ? implode(',',json_decode($data['available_list'], true)) : '';
        SysSmsGateway::create($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "添加 短信通道");
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Factory|View
     */
    public function edit($id)
    {
        $gateway = SysSmsGateway::find($id)->toArray();
        $gateway['available_list_copy'] = !empty($gateway['available_list']) ? explode(',',$gateway['available_list']) : [] ;
        $settings = SysSmsSetting::where('status', 1)->get();
        return view('admin/Base/sms/edit', compact('settings', 'gateway'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return array
     */
    public function update(Request $request, $id)
    {
        $data = $this->get_params($request, ['name', 'unique_name', 'available_list', ['status', 0], 'desc'], false);
        $data['available_list'] = isset($data['available_list']) ? trim(implode(',',json_decode($data['available_list'], true)), ',') : '';
        SysSmsGateway::where('id', $id)->update($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "修改 短信通道");
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param $ids
     * @return array
     */
    public function destroy(Request $request, $ids)
    {
        $ids = explode(',', $ids);

        SysSmsGateway::destroy($ids);
        $this->log(__CLASS__, __FUNCTION__, $request, "删除 通道");
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * pagination
     *
     * @param Request $request
     * @return mixed
     */
    public function pagination(Request $request){
        $selectInfo = $this->get_params($request, ['type','phone','from_created_at','to_created_at']);
        $condition = $this->getPagingList($selectInfo, ['type'=>'=','phone'=>'=','from_created_at'=>'>=','to_created_at'=>'<=']);
        $rows = $request->rows;
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder ?? 'desc';
        $result = SysSmsLog::where($condition)
            ->orderBy($order, $sort)
            ->paginate($rows);
        return $result;
    }

    /**
     * pagination
     *
     * @param Request $request
     * @return mixed
     */
    public function setting_pagination(Request $request){
        $rows = $request->rows;
        return SysSmsLog::orderBy('id', 'desc')->paginate($rows);

    }

    //////////////////////////////////////////////////////SmsSetting////////////////////////////////////////////////////
    /**
     * @param Request $request
     * @return Factory|View
     */
    public function sms_setting(Request $request)
    {
        $Settings = SysSmsSetting::orderBy('status', 'desc')->orderBy('id', 'asc')->get();
        $this->log(__CLASS__, __FUNCTION__, $request, "短信配置 列表");
        return view('admin/Base/sms/sms_setting', compact('Settings'));
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function addSmsSetting(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "添加 短信配置");
        return view('admin/Base/sms/addSmsSetting');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function saveSmsSetting(Request $request)
    {
        $data = $this->get_params($request, ['name', 'unique_name', ['status', 0], 'managerAddress', 'setting', 'return_code', 'desc']);
        SysSmsSetting::create($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "添加 短信配置");
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * @param Request $request
     * @param $id
     * @return Factory|View
     */
    public function editSmsSetting(Request $request, $id)
    {
        $Setting = SysSmsSetting::find($id);
        return view('admin/Base/sms/editSmsSetting', compact('Setting'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return array
     */
    public function updateSmsSetting(Request $request, $id)
    {
        $data = $this->get_params($request, ['name', 'unique_name', ['status', 0], 'managerAddress', 'setting', 'return_code', 'desc'], false);
        SysSmsSetting::where('id', $id)->update($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "修改 短信配置");
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * pagination
     *
     * @param Request $request
     * @return mixed
     */
    public function gatewayPagination(Request $request){
        $rows = $request->rows;
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder ?? 'desc';
        return SysSmsGateway::orderBy($order, $sort)
            ->paginate($rows);
    }

    /////////////////////////////////////////////测试发送////////////////////////////////////////////////
    /**
     * @return Factory|View
     */
    public function testSms()
    {
        $gateways = SysSmsGateway::get();
        return view('admin/Base/sms/testSms', compact('gateways'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function sendTestSms(Request $request)
    {
        $data = $this->get_params($request, ['phone', 'content', 'params', 'gateway']);
        $data['params'] = $data['params'] ?? [];
        return (new SMS)::send($data['phone'], $data['content'], $data['params'], $data['gateway']);
    }


}
