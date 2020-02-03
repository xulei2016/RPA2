<?php

namespace App\Http\Controllers\Admin\Revisit;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\base\BaseAdminController;
use App\Models\Admin\Api\Revisit\Customer\RpaRevisitCustomers;
use App\Models\Admin\Api\Revisit\Customer\RpaRevisitCustomerRecords as Records;
use Mockery\Exception;

use Illuminate\Support\Facades\DB;

class CustomerRevisitController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('Admin.Func.Revisit.Customer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        return view('Admin.Func.Revisit.Customer.edit', compact('id'));
    }

    /**
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAudio($id)
    {
        try {
            $data = Records::where('record_id', $id)->orderBy('created_at', 'desc')->firstOrFail();
            if (!Storage::disk('local')->exists($data->record_voice)) {
                return $this->ajax_return(500, '回访文件不存在！');
            }
            $file = Storage::disk('local')->get($data->record_voice);
            return $file;
        } catch (\Exception $e) {
            return $this->ajax_return(500, '回访记录不存在！');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        //
        return view('Admin.Func.Revisit.Customer.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return array
     */
    public function update(Request $request, $id)
    {
        //
        $data = $this->get_params($request, [['status', 1], ['bz','']]);

        //更新record/ revisit customer
        DB::beginTransaction();
        try{
            RpaRevisitCustomers::where([['id', '=', $id], ['status','<','3']])->update(['status'=>$data['status']]);
            Records::where([['record_id', '=', $id], ['status','=','0']])->update(['status'=>$data['status'], 'desc'=>$data['bz']]);
            DB::commit();
            return $this->ajax_return(200, '操作成功！');
        } catch(\Exception $e){
            DB::rollBack();
            return $this->ajax_return(500, '操作失败！', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * show
     * @param Request $request
     * @return
     */
    public function pagination(Request $request)
    {
        $rows = $request->rows;
        $data = $this->get_params($request, ['yybName', 'status', 'name'], false);
//        $conditions = $this->getPagingList($data, ['yybName' => 'like', 'status' => '=', 'customer' => 'like']);
        $conditions = [];
        if (isset($data['yybName'])) {
            $conditions[] = ['rpa_customer_managers.yybName', 'like', "%" . $data['yybName'] . "%"];
        }
        if (isset($data['status'])) {
            $conditions[] = ['rpa_revisit_customers.status', '=', $data['status']];
        }
        if (isset($data['name'])) {
            $conditions[] = ['rpa_customer_managers.name', 'like', "%" . $data['name'] . "%"];
        }
        $order = $request->sort ?? 'rpa_revisit_customers.status';
        $sort = $request->sortOrder;
        return RpaRevisitCustomers::where($conditions)
            ->leftJoin('rpa_customer_managers', 'rpa_revisit_customers.customer_id', '=', 'rpa_customer_managers.id')
            ->select(['rpa_customer_managers.fundsNum', 'rpa_customer_managers.name', 'rpa_customer_managers.yybName', 'rpa_revisit_customers.status', 'rpa_revisit_customers.id'])
            ->orderBy($order, $sort)
            ->paginate($rows);
    }
}
