<?php

namespace App\Http\Controllers\Admin\Base\CallCenter;

use App\Models\Admin\Base\CallCenter\SysRecordDetail;
use Illuminate\Http\Request;

class RecordDetailController extends BaseController
{
    private $view_prefix = "admin.base.callCenter.recordDetail.";

    public function index(Request $request) {
        $record_id = $request->get('record_id');
        return view($this->view_prefix.'index', [
            'record_id' => $record_id
        ]);
    }

    public function pagination(Request $request){
        $selectInfo = $this->get_params($request, ['record_id']);
        $condition = $this->getPagingList($selectInfo, ['record_id'=>'=']);
        $rows = $request->rows;
        $order = ($request->sort ?? 'id');
        $sort = 'desc';
        $result = SysRecordDetail::from('sys_call_center_record_details as sr')
            ->where($condition)
            ->leftJoin('sys_admins as sa', 'sr.manager_id', '=', 'sa.id')
            ->leftJoin('sys_call_center_customers as sc', 'sr.customer_id', '=', 'sc.id')
            ->select(['sr.*', 'sa.realName','sc.name'])
            ->orderBy($order, $sort)
            ->paginate($rows);
        return $result;
    }

    /**
     * 根据id获取聊天记录
     */
    public function getRecordById(Request $request){
        $record_id = $request->get('record_id');
        $list = SysRecordDetail::where('record_id', $record_id)->get();
        return $this->ajax_return(200, 'success', $list->toArray());
    }

    /**
     * 根据manager和customer获取最新20条记录
     */
    public function getRecordList(Request $request){
        $manager_id = $request->get('manager_id');
        $customer_id = $request->get('customer_id');
        $list = SysRecordDetail::where(['manager_id'=>$manager_id,'customer_id' => $customer_id, 'type' => 'message'])->orderBy('id', 'desc')->limit(20)->get();
        return $this->ajax_return(200, 'success', $list->toArray());
    }
}