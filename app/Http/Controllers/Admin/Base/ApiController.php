<?php

namespace App\Http\Controllers\Admin\Base;

use App\Http\Controllers\base\BaseAdminController;
use App\Models\Admin\Base\SysApiip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends BaseAdminController
{
    //api列表
    public function index(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 api列表页");

        return view('admin.base.api.index');
    }
    //添加页面
    public function create(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "添加 api页");

        return view('admin.base.api.add');
    }
    //添加
    public function store(Request $request)
    {
        $data = $this->get_params($request, ['api','url','method','desc','jsondata1','jsondata2']);

    }
    //分页
    public function pagination(Request $request){
        $selectInfo = $this->get_params($request, ['api','from_created_at','to_created_at']);
        $condition = $this->getPagingList($selectInfo, ['api'=>'=','from_created_at'=>'>=','to_created_at'=>'<=']);
        $rows = $request->rows;
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder ?? 'desc';
        $result = SysApiip::where($condition)
            ->orderBy($order, $sort)
            ->paginate($rows);
        return $result;
    }
}
