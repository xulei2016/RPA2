<?php

namespace App\Http\Controllers\Admin\Base;

use App\models\admin\base\SysImprovement;
use Illuminate\Http\Request;
use App\Http\Controllers\Base\BaseAdminController;

/**
 * ImprovementController
 * @author hsu lay
 */
class ImprovementController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.improvement.index');
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
     * @param  \App\models\admin\base\SysImprovement  $sysImprovement
     * @return \Illuminate\Http\Response
     */
    public function show(SysImprovement $sysImprovement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\models\admin\base\SysImprovement  $sysImprovement
     * @return \Illuminate\Http\Response
     */
    public function edit(SysImprovement $sysImprovement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\admin\base\SysImprovement  $sysImprovement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SysImprovement $sysImprovement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\admin\base\SysImprovement  $sysImprovement
     * @return \Illuminate\Http\Response
     */
    public function destroy(SysImprovement $sysImprovement)
    {
        //
    }
}
