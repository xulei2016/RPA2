<?php

namespace App\Http\Controllers\Admin\Revisit;

use App\Models\Admin\Func\rpa_customer_manager;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Excel;

use App\Http\Controllers\base\BaseAdminController;
use App\Models\Admin\Api\Revisit\Customer\RpaRevisitCustomers;
use App\Models\Admin\Api\Revisit\Customer\RpaRevisitCustomerRecords as Records;
use Illuminate\View\View;

/**
 * Class CustomerRevisitController
 * @package App\Http\Controllers\Admin\Revisit
 */
class CustomerRevisitController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index()
    {
        return view('Admin.Func.Revisit.Customer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Factory|View
     */
    public function show($id)
    {
        $record = RpaRevisitCustomers::leftJoin('rpa_customer_managers', 'rpa_revisit_customers.customer_id', '=', 'rpa_customer_managers.id')
            ->select([
                'rpa_customer_managers.fundsNum',
                'rpa_customer_managers.name',
                'rpa_customer_managers.sync_jjr_num',
                'rpa_customer_managers.sync_jjr_name'
            ])->find($id);
        return view('Admin.Func.Revisit.Customer.show', compact('id', 'record'));
    }

    /**
     * @param $id
     * @return array|Factory|View
     */
    public function getAudio($id)
    {
        try {
            $data = Records::where('record_id', $id)->orderBy('created_at', 'desc')->firstOrFail();
            if (!Storage::disk('local')->exists($data->record_voice)) {
                return $this->ajax_return(500, '回访文件不存在！');
            }
            return Storage::disk('local')->get($data->record_voice);
        } catch (\Exception $e) {
            return $this->ajax_return(500, '回访记录不存在！');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Factory|View
     */
    public function edit($id)
    {
        $record = RpaRevisitCustomers::leftJoin('rpa_customer_managers', 'rpa_revisit_customers.customer_id', '=', 'rpa_customer_managers.id')
            ->select([
                'rpa_customer_managers.fundsNum',
                'rpa_customer_managers.name',
                'rpa_customer_managers.sync_jjr_num',
                'rpa_customer_managers.sync_jjr_name'
            ])->find($id);
        return view('Admin.Func.Revisit.Customer.edit', compact('id','record'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function update(Request $request, $id)
    {
        $data = $this->get_params($request, [['status', 1], ['bz', '']]);

        //更新record/ revisit customer
        DB::beginTransaction();
        try {
            $customer = RpaRevisitCustomers::find($id);
            $customer_manager = rpa_customer_manager::find($customer->customer_id);

            //客户经理、回访人、复核人不能是同一人
            if( auth()->guard('admin')->user()->realName == $customer->reviser
                || auth()->guard('admin')->user()->realName == $customer_manager->customerManagerName ){
                return $this->ajax_return(500, '审核人不能与经理人或回访人是同一人！');
            }
            RpaRevisitCustomers::where([['id', '=', $id], ['status', '<', '3']])->update([
                'status' => $data['status'],
                'checker' => auth()->guard('admin')->user()->realName,
                'check_at' => date('Y-m-d H:i:s'),
                'failID' => (1 == $data['status']) ? 110 : 0,
                'failDesc' => $data['bz']
            ]);
            Records::where([['record_id', '=', $id], ['status', '=', '0']])->update(['status' => $data['status'], 'desc' => $data['bz']]);
            DB::commit();
            return $this->ajax_return(200, '操作成功！');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->ajax_return(500, '操作失败！', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * show
     * @param Request $request
     * @return array
     */
    public function pagination(Request $request)
    {
        $rows = $request->rows;
        $data = $this->get_params($request, ['yybName', 'status', 'name', 'reviser', 'checker', 'from_created_at', 'to_created_at'], false);

        $conditions = $this->serializeCondition($data);
        $order = $request->sort ?? 'rpa_revisit_customers.status';
        $sort = $request->sortOrder;
        return RpaRevisitCustomers::where($conditions)
            ->leftJoin('rpa_customer_managers', 'rpa_revisit_customers.customer_id', '=', 'rpa_customer_managers.id')
            ->leftJoin('sys_dictionaries', 'rpa_revisit_customers.failID', '=', 'sys_dictionaries.value')
            ->select([
                'rpa_customer_managers.fundsNum',
                'rpa_customer_managers.name',
                'rpa_customer_managers.sync_yyb_name as yybName',
                'rpa_customer_managers.KHRQ',
                'rpa_customer_managers.sync_jlr_name as customerManagerName',
                'rpa_revisit_customers.status',
                'rpa_revisit_customers.reviser',
                'rpa_revisit_customers.checker',
                'rpa_revisit_customers.id',
                'sys_dictionaries.dict_prompt',
                'rpa_revisit_customers.failDesc',
                'rpa_revisit_customers.revisit_at',
                'rpa_revisit_customers.check_at',
            ])
            ->orderBy($order, $sort)
            ->paginate($rows);
    }

    /**
     * export
     * @param Request $request
     */
    public function export(Request $request)
    {
        $params = $this->get_params($request, ['yybName', 'status', 'name', 'reviser', 'checker','from_created_at', 'to_created_at'], false);

        $conditions = $this->serializeCondition($params);

        $order = $request->sort ?? 'rpa_revisit_customers.status';
        $sort = $request->sortOrder;
        if (isset($params['ids'])) {
            $data = RpaRevisitCustomers::where($conditions)
                ->leftJoin('rpa_customer_managers', 'rpa_revisit_customers.customer_id', '=', 'rpa_customer_managers.id')
                ->leftJoin('sys_dictionaries', 'rpa_revisit_customers.failID', '=', 'sys_dictionaries.value')
                ->whereIn('rpa_customer_managers.id', explode(',', $params['ids']))
                ->select([
                    'rpa_customer_managers.fundsNum', 
                    'rpa_customer_managers.name', 
                    'rpa_customer_managers.sync_yyb_name', 
                    'rpa_customer_managers.sync_jjr_name', 
                    'rpa_revisit_customers.reviser',
                    'rpa_revisit_customers.revisit_at',
                    'rpa_revisit_customers.checker',
                    'rpa_revisit_customers.check_at',
                    'sys_dictionaries.dict_prompt',
                    'rpa_revisit_customers.failDesc'
                    ])
                ->orderBy($order, $sort)
                ->get()
                ->toArray();
        } else {
            $data = RpaRevisitCustomers::where($conditions)
                ->leftJoin('rpa_customer_managers', 'rpa_revisit_customers.customer_id', '=', 'rpa_customer_managers.id')
                ->leftJoin('sys_dictionaries', 'rpa_revisit_customers.failID', '=', 'sys_dictionaries.value')
                ->select([
                    'rpa_customer_managers.fundsNum', 
                    'rpa_customer_managers.name', 
                    'rpa_customer_managers.sync_yyb_name', 
                    'rpa_customer_managers.sync_jjr_name', 
                    'rpa_revisit_customers.reviser',
                    'rpa_revisit_customers.revisit_at',
                    'rpa_revisit_customers.checker',
                    'rpa_revisit_customers.check_at',
                    'sys_dictionaries.dict_prompt',
                    'rpa_revisit_customers.failDesc'
                    ])
                ->orderBy($order, $sort)
                ->get()
                ->toArray();
        }

        $cellData = [];
        $cellData[] = array_keys($data[0]);
        foreach ($data as $k => $info) {
            array_push($cellData, array_values($info));
        }

        $s = Excel::create('居间客户回访列表', function ($excel) use ($cellData) {
            $excel->sheet('列表', function ($sheet) use ($cellData) {
                $sheet->rows($cellData);
            });
        })->export('xls');
    }

    /**
     * @param $data
     * @return array
     */
    private function serializeCondition($data)
    {
        $conditions = [];

        $dept_id = auth()->guard('admin')->user()->dept_id;
        $yyb = DB::table('sys_depts')->where('id', $dept_id)->pluck('yyb_hs');
        $yyb = !$yyb->isEmpty() ? $yyb[0] : '' ;

        $conditions[] = ['sys_dictionaries.type', '=', 'failType'];
        $conditions[] = ['rpa_customer_managers.sync_jjr_num', '!=', ' '];
        //是否总部权限
        if($yyb && '1001' !== $yyb && '1009' !== $yyb && '10005' !== $yyb){
            $conditions[] = ['rpa_customer_managers.sync_yyb_num', '=', $yyb];
        }
        //是否IB权限
        if($yyb && '10005' == $yyb){
            $conditions[] = ['rpa_customer_managers.sync_yyb_name', 'like', '%IB%'];
        }
        if (isset($data['yybName'])) {
            $conditions[] = ['rpa_customer_managers.sync_yyb_name', 'like', "%" . $data['yybName'] . "%"];
        }
        if (isset($data['from_created_at'])) {
            $conditions[] = ['rpa_revisit_customers.created_at', '>=', $data['from_created_at']];
        }
        if (isset($data['to_created_at'])) {
            $conditions[] = ['rpa_revisit_customers.created_at', '<=', $data['to_created_at'] . " 23:59:59"];
        }
        if (isset($data['status'])) {
            $conditions[] = ['rpa_revisit_customers.status', '=', $data['status']];
        }
        if (isset($data['reviser'])) {
            $conditions[] = ['rpa_revisit_customers.reviser', '=', $data['reviser']];
        }
        if (isset($data['checker'])) {
            $conditions[] = ['rpa_revisit_customers.checker', '=', $data['checker']];
        }
        if (isset($data['name'])) {
            if (preg_match("/^\d*$/", $data['name'])) {
                $conditions[] = ['rpa_customer_managers.fundsNum', '=', $data['name']];
            } else {
                $conditions[] = ['rpa_customer_managers.name', 'like', "%" . $data['name'] . "%"];
            }
        }

        return $conditions;
    }
}
