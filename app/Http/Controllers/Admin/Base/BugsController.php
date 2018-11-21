<?php

namespace App\Http\Controllers\Admin\Base;

use App\Models\Admin\Base\SysBug;
use Illuminate\Http\Request;
use App\Http\Controllers\Base\BaseAdminController;

/**
 * BugsController
 * @author hsu lay
 */
class BugsController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin.bugs.index');
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
     * @param  \App\models\admin\base\SysBug  $sysBug
     * @return \Illuminate\Http\Response
     */
    public function show(SysBug $sysBug)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\models\admin\base\SysBug  $sysBug
     * @return \Illuminate\Http\Response
     */
    public function edit(SysBug $sysBug)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\admin\base\SysBug  $sysBug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SysBug $sysBug)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\admin\base\SysBug  $sysBug
     * @return \Illuminate\Http\Response
     */
    public function destroy(SysBug $sysBug)
    {
        //
    }
}
