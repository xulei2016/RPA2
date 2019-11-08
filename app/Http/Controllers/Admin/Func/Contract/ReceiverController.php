<?php

namespace App\Http\Controllers\Admin\Func\Contract;

use App\Models\Admin\Func\Contract\RpaContractReceiver;
use Illuminate\Http\Request;

/**
 * 接收者管理
 * Class ReceiverController
 * @package App\Http\Controllers\Admin\Func\Contract
 */
class ReceiverController extends BaseController
{
    /**
     * @var string 页面前缀
     */
    protected $view_prefix = "admin.func.contract.receiver.";

    /**
     * 首页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "合约-接收者 列表页");
        return view($this->view_prefix.'index');
    }

    /**
     * 数据
     * @param Request $request
     * @return
     */
    public function pagination(Request $request) {
        $selectInfo = $this->get_params($request, []);
        $condition = $this->getPagingList($selectInfo, []);
        $rows = $request->rows;
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder ?? 'asc';
        $list = RpaContractReceiver::where($condition)->orderBy($order, $sort)->paginate($rows);
        return $list;
    }

    /**
     * 新增页
     */
    public function create()
    {
        return parent::create(); // TODO: Change the autogenerated stub
    }

    /**
     * 新增保存
     * @param Request $request
     * @return array
     */
    public function store(Request $request){
        $data = $this->get_params($request, ['name','desc', 'email']);
        $data['created_by'] = auth()->guard()->user()->id;
        $data['updated_by'] = auth()->guard()->user()->id;
        $result = RpaContractReceiver::create($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "新增 合约-接收者");
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
        $info = RpaContractReceiver::where('id', $id)->first();
        return view($this->view_prefix.'edit', ['info' => $info]);
    }

    /**
     * 更新数据
     * @param Request $request
     * @return array
     */
    public function update(Request $request){
        $data = $this->get_params($request, ['name', 'desc', 'email', 'id', ['status', 0]]);
        $data['updated_by'] = auth()->guard()->user()->id;
        $result = RpaContractReceiver::where('id', $data['id'])->update($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "修改 合约-接收者");
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
    public function destroy(Request $request, $ids){
        $ids = explode(',', $ids);
        RpaContractReceiver::destroy($ids);
        $this->log(__CLASS__, __FUNCTION__, $request, "删除 合约-接收者");
        return $this->ajax_return('200', '操作成功');
    }

    /**
     * 查询界面
     * @param Request $request
     * @param $id
     */
    public function show(Request $request, $id) {}

    /**
     * 邮件发送
     */
    public function email(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "删除 合约-接收者邮件测试");
        $result = RpaContractReceiver::where('id', $request->id)->first();
        if(!$result) return $this->ajax_return(500, '未找到该记录');
    }
}