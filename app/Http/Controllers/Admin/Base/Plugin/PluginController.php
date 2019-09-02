<?php
namespace App\Http\Controllers\Admin\Base\Plugin;

use App\Models\Admin\Base\Plugin\SysPlugin;
use App\Models\Admin\Base\Plugin\SysPluginVersion;
use Illuminate\Http\Request;

/**
 * 插件管理主控制器
 * Class PlugInController
 * @package App\Http\Controllers\Admin\Base\PlugIn
 */
class PluginController extends BaseController {

    private $view_prefix = "admin.base.plugin.plugin.";

    /**
     * 列表页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request) {
        $this->log(__CLASS__, __FUNCTION__, $request, "插件 列表页");
        return view($this->view_prefix.'index');
    }

    /**
     * 列表数据
     * @param Request $request
     * @return mixed
     */
    public function pagination(Request $request){
        $selectInfo = $this->get_params($request, ['name']);
        $condition = $this->getPagingList($selectInfo, ['name'=>'like']);
        $rows = $request->rows;
        $order = ($request->sort ?? 'id');
        $sort = 'asc';
        $result = SysPlugin::from('sys_plugins as sp')
            ->where($condition)
            ->orderBy($order, $sort)
            ->paginate($rows);
        return $result;
    }

    /**
     * 新增页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){
        return view($this->view_prefix.'add');
    }

    /**
     * 新增数据
     * @param Request $request
     * @return array
     */
    public function store(Request $request){
        $data = $this->get_params($request, ['name','desc']);
        SysPlugin::create($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "新增 插件");
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * 编辑界面
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id){
        $plugin = SysPlugin::where('id', $id)->first();
        return view($this->view_prefix.'edit', ['plugin' => $plugin]);
    }

    /**
     * 更新数据
     * @param Request $request
     * @param $id
     * @return array
     */
    public function update(Request $request){
        $data = $this->get_params($request, ['name','desc','id',['status',0]]);
        $result = SysPlugin::where('id', $data['id'])->update($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "修改 插件");
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * 删除
     * @param Request $request
     * @param $ids
     * @return array
     */
    public function destroy(Request $request, $ids){
        $ids = explode(',', $ids);
        SysPlugin::destroy($ids);
        $this->log(__CLASS__, __FUNCTION__, $request, "删除 插件");
        return $this->ajax_return('200', '操作成功');
    }

    /**
     * 查询页面
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $id) {
        $versions = SysPluginVersion::where(["status" => 1,'pid' => $id])->orderBy('id', 'desc')->get();
        return view($this->view_prefix.'show',['versions' => $versions]);
    }

}