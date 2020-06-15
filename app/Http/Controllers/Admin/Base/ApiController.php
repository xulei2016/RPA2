<?php

namespace App\Http\Controllers\Admin\Base;

use App\Http\Controllers\base\BaseAdminController;
use App\Models\Admin\Api\ApiList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
        $this->log(__CLASS__, __FUNCTION__, $request, "添加 api");
        $data = $this->get_params($request, ['api','url','method','desc','state','black_list','white_list']);
        ApiList::create($data);
        return $this->ajax_return(200, '操作成功！');
    }
    //修改页面
    public function edit(Request $request, $id)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "更新 api页面");
        $sysApiip = ApiList::find($id);
        $sysApiip['black_list'] = isset($sysApiip['black_list']) ? json_decode($sysApiip['black_list'], false) :'';
        $sysApiip['white_list'] = isset($sysApiip['white_list']) ? json_decode($sysApiip['white_list'], false) :'';
        return view('admin.base.api.edit',['apiip' => $sysApiip]);
    }
    //更新
    public function update(Request $request, $id)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "更新 api");
        $data = $this->get_params($request, ['api','url','method','desc','state','black_list','white_list']);
        ApiList::where('id',$id)->update($data);
        Cache::forget($data['api']);
        return $this->ajax_return(200, '操作成功！');
    }
    //查看参数
    public function show(Request $request, $id)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 api参数");
        $sysApiip = ApiList::find($id);
        $sysApiip['black_list'] = $sysApiip['black_list'] ? json_decode($sysApiip['black_list']) :'';
        $sysApiip['white_list'] = $sysApiip['white_list'] ? json_decode($sysApiip['white_list']) :'';
        return view('admin.base.api.show',['apiip' => $sysApiip]);
    }
    //删除
    public function destroy(Request $request, $ids)
    {
        $ids = explode(',', $ids);
        $result = ApiList::destroy($ids);
        $this->log(__CLASS__, __FUNCTION__, $request, "删除 api");
        return $this->ajax_return(200, '操作成功！');
    }
    //分页
    public function pagination(Request $request){
        $selectInfo = $this->get_params($request, ['api','desc','from_created_at','to_created_at']);
        $condition = $this->getPagingList($selectInfo, ['api'=>'like','desc'=>'like','from_created_at'=>'>=','to_created_at'=>'<=']);
        $rows = $request->rows;
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder ?? 'desc';
        $result = ApiList::where($condition)
            ->orderBy($order, $sort)
            ->paginate($rows);
        return $result;
    }
}
