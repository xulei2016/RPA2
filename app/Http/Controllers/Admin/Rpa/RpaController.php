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
    public function index()
    {
        return view('admin.rpa.center.index');
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit(rpa_maintenance $rpa_maintenance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\admin\rpa\rpa_maintenance  $rpa_maintenance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, rpa_maintenance $rpa_maintenance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\admin\rpa\rpa_maintenance  $rpa_maintenance
     * @return \Illuminate\Http\Response
     */
    public function destroy(rpa_maintenance $rpa_maintenance)
    {
        //
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
}
