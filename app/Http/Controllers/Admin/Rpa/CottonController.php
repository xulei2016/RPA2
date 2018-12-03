<?php

namespace App\Http\Controllers\admin\rpa;

use Illuminate\Http\Request;
use App\Http\Controllers\Base\BaseAdminController;

/**
 * CottonController
 * @author hsu lay
 */
class CottonController extends BaseAdminController
{
    //task name
    private $task_name;

    /**
     * __CONSTRUCT
     */
    public function __CONSTRUCT()
    {
        $this->task_name = 'cottonExcel';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.rpa.cotton.index');
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * showExcel
     */
    public function showExcel(Request $request)
    {
        return view('admin.rpa.cotton.importExcel');
    }

    /**
     * importExcel
     */
    public function importExcel(Request $request)
    {
        
    }
}
