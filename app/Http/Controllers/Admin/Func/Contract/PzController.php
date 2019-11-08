<?php

namespace App\Http\Controllers\Admin\Func\Contract;

use App\Models\Admin\Func\Contract\RpaContractDetail;
use App\Models\Admin\Func\Contract\RpaContractJys;
use App\Models\Admin\Func\Contract\RpaContractPz;
use Illuminate\Http\Request;

/**
 * 品种管理
 * Class PzController
 * @package App\Http\Controllers\Admin\Func\Contract
 */
class PzController extends BaseController
{
    /**
     * @var string 页面前缀
     */
    protected $view_prefix = "admin.func.contract.pz.";

    /**
     * 首页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "合约-品种 列表页");
        $jys = RpaContractJys::get();
        return view($this->view_prefix.'index', ['jys' => $jys]);
    }

    /**
     * 数据
     * @param Request $request
     * @return
     */
    public function pagination(Request $request) {
        $selectInfo = $this->get_params($request, ['jys_id']);
        $condition = $this->getPagingList($selectInfo, ['jys_id' => '=']);
        $rows = $request->rows;
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder ?? 'asc';
        $list = RpaContractPz::where($condition)->orderBy($order, $sort)->paginate($rows);
        foreach ($list as &$v) {
            $jys = RpaContractJys::where('id', $v->jys_id)->first();
            $v->jys = $jys?$jys->name:'暂无';
        }
        return $list;
    }

    /**
     * 新增页
     */
    public function create()
    {
        $list = RpaContractJys::get();
        return view($this->view_prefix.'add', ['jys' => $list]);
    }

    /**
     * 新增保存
     * @param Request $request
     * @return array
     */
    public function store(Request $request){
        $data = $this->get_params($request, ['name','desc','code','jys_id']);
        $data['created_by'] = auth()->guard()->user()->id;
        $data['updated_by'] = auth()->guard()->user()->id;
        $result = RpaContractPz::create($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "新增 合约-详细");
        if($result) {
            return $this->ajax_return(200, '操作成功！');
        } else {
            return $this->ajax_return(500, '保存失败！');
        }
    }

    /**
     * 编辑页
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id){
        $list = RpaContractJys::get();
        $info = RpaContractPz::where('id', $id)->first();
        return view($this->view_prefix.'edit', ['info' => $info, 'jys' => $list]);
    }

    /**
     * 更新数据
     * @param Request $request
     * @return array
     */
    public function update(Request $request){
        $data = $this->get_params($request, ['id','name','desc','code','jys_id',['status', 0]]);
        $data['updated_by'] = auth()->guard()->user()->id;
        $result = RpaContractPz::where('id', $data['id'])->update($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "修改 合约-品种");
        if($result) {
            return $this->ajax_return(200, '操作成功！');
        } else {
            return $this->ajax_return(500, '保存失败！');
        }
    }

    /**
     * 删除
     * @param Request $request
     * @param $ids
     */
    public function destroy(Request $request, $ids){}

    /**
     * 查询界面
     * @param Request $request
     * @param $id
     */
    public function show(Request $request, $id) {}

    /**
     * 根据交易所id获取品种
     * @param Request $request
     * @return string
     */
    public function getByJys(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "根据交易所id获取品种");
        $list = RpaContractPz::where('jys_id', $request->jys_id)->get();
        $html = "<option value=''>未选择</option>";
        foreach ($list as $v) {
            $html .= "<option value='".$v->id."'>".$v->name."</option>";
        }
        return $html;
    }
}