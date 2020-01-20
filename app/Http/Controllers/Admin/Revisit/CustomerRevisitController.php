<?php

namespace App\Http\Controllers\Admin\Revisit;

use App\Http\Controllers\base\BaseAdminController;
use Illuminate\Http\Request;

use App\Models\Admin\Api\Revisit\Customer\RpaRevisitCustomers;
use App\Models\Admin\Api\Revisit\Customer\RpaRevisitCustomerRecords as Records;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception;

class CustomerRevisitController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     * @return array
     */
    public function show($id)
    {
        //
        try {
            $data = Records::where('record_id', $id)->orderBy('created_at', 'desc')->firstOrFail();
            if(!Storage::disk('local')->exists($data->record_voice)){
                return $this->ajax_return(500, '回访文件不存在！');
            }
            $file = Storage::disk('local')->get($data->record_voice);

            $file = 'F:\projects\02.Projects\02.RPA\02.Trunk\01.Web\RPA2\storage\app\rpa\revisit\customer\110003616\fmdhLebqE0qE4uICgd0zokYMdeqh6CZU2XqUSBKn.wav';
            dd(stat($file));
            return $file;
            return view('Admin.Func.Revisit.Customer.edit', compact('data', 'file'));
        }catch (\Exception $e){
            return $this->ajax_return(500, '回访记录不存在！');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
        if(isset($data['yybName'])){
            $conditions[] = ['rpa_customer_managers.yybName', 'like', "%".$data['yybName']."%"];
        }
        if(isset($data['status'])){
            $conditions[] = ['rpa_revisit_customers.status', '=', $data['status']];
        }
        if(isset($data['name'])){
            $conditions[] = ['rpa_customer_managers.name', 'like', "%".$data['name']."%"];
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
