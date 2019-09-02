<?php

namespace App\Http\Controllers\Admin\Base;

use App\Models\Admin\Base\SysBug;
use App\Models\Admin\Base\SysMessage;
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

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "添加 BUG");
        $data = $this->get_params($request, ['title','content','desc']);
        SysBug::create($data);

        //发送通知
        $data = [
            'mode' => 1,
            'user' => 1,
            'title' => "bug提交",
            'type' => 3,
            'content' => '有一个新的bug提交',
            'add_time' => date('Y-m-d H:i:s',time())
        ];
        SysMessage::create($data);


        return $this->ajax_return(200, '操作成功！');
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
