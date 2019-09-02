<?php

namespace App\Http\Controllers\Admin\Base\CallCenter;

use App\Models\Admin\Base\CallCenter\SysCustomer;
use App\Models\Admin\Base\CallCenter\SysManager;
use App\Models\Admin\Base\CallCenter\SysRecord;
use App\Models\Admin\Base\CallCenter\SysRecordDetail;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RecordController extends BaseController
{
    private $view_prefix = "admin.base.callCenter.record.";

    public function index(){
        $managers = SysManager::get();
        $customers = SysCustomer::get();
        return view($this->view_prefix.'index', [
            'managers' => $managers,
            'customers' => $customers
        ]);
    }

    /**
     * 分页列表
     * @param Request $request
     * @return mixed
     */
    public function pagination(Request $request){
        $selectInfo = $this->get_params($request, ['manager_id', 'customer_id']);
        $condition = $this->getPagingList($selectInfo, ['manager_id'=>'=', 'customer_id'=>'=']);
        $rows = $request->rows;
        $order = ($request->sort ?? 'id');
        $sort = 'desc';
        $result = SysRecord::from('sys_call_center_records as sr')
            ->where($condition)
            ->leftJoin('sys_admins as sa', 'sr.manager_id', '=', 'sa.id')
            ->leftJoin('sys_call_center_customers as sc', 'sr.customer_id', '=', 'sc.id')
            ->select(['sr.*', 'sa.realName','sc.name'])
            ->orderBy($order, $sort)
            ->paginate($rows);
        return $result;
    }

    /**
     * 导出
     * @param Request $request
     */
    public function export(Request $request){
        $param = $this->get_params($request, ['manager_id', 'customer_id']);
        $condition = $this->getPagingList($param, ['manager_id'=>'=', 'customer_id' => '=']);
        $modelBuild = SysRecord::from('sys_call_center_records as sr')
            ->where($condition)
            ->leftJoin('sys_admins as sa', 'sr.manager_id', '=', 'sa.id')
            ->leftJoin('sys_call_center_customers as sc', 'sr.customer_id', '=', 'sc.id')
            ->select(['sr.*', 'sa.realName as realName','sc.name']);
        if($request->has('id')){
            $data = $modelBuild->whereIn('sr.id', explode(',',$request->get('id')))->get()->toArray();
        }else{
            $data = $modelBuild->get()->toArray();
        }
        $cellData = [];
        $cellData[] = array_keys($data[0]);
        foreach($data as $k => $info){
            array_push($cellData, array_values($info));
        }
        Excel::create('聊天记录',function($excel) use ($cellData){
            $excel->sheet('聊天记录', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
}