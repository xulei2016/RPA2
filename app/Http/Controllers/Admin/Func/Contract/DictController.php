<?php


namespace App\Http\Controllers\Admin\Func\Contract;


use App\Models\Admin\Func\Contract\RpaContractDict;
use Illuminate\Http\Request;

class DictController extends BaseController
{
    protected $view_prefix = "admin.func.contract.dict.";

    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "合约-配置信息 列表页");
        $dict = RpaContractDict::get();
        return view($this->view_prefix.'index', ['dict' => $dict]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        foreach ($data as $k => $v) {
            RpaContractDict::where('name','=', $k)->update(['value' => $v]);
        }
        return $this->ajax_return(200, '操作成功');
    }
}