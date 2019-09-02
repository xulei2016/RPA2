<?php
namespace App\Http\Controllers\Admin\Base\Plugin;

use App\Models\Admin\Base\Plugin\SysPlugin;
use App\Models\Admin\Base\Plugin\SysPluginVersion;
use Illuminate\Http\Request;

/**
 * 插件版本管理
 * Class VersionController
 * @package App\Http\Controllers\Admin\Base\Plugin
 */
class VersionController extends BaseController
{
    private $view_prefix = "admin.base.plugin.version.";

    /**
     * 列表页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request) {
        $this->log(__CLASS__, __FUNCTION__, $request, "插件版本 列表页");
        return view($this->view_prefix.'index');
    }

    /**
     * 列表数据
     * @param Request $request
     * @return mixed
     */
    public function pagination(Request $request){
        $selectInfo = $this->get_params($request, ['version', 'name']);
        $condition = $this->getPagingList($selectInfo, ['version'=>'like', 'name' => 'like']);
        $rows = $request->rows;
        $order = ($request->sort ?? 'id');
        $sort = 'asc';
        $result = SysPluginVersion::from('sys_plugin_versions as spv')
            ->leftJoin('sys_plugins as sp', 'spv.pid', '=', 'sp.id')
            ->where($condition)
            ->select(['spv.*','sp.name'])
            ->orderBy($order, $sort)
            ->paginate($rows);
        return $result;
    }

    /**
     * 新增页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){
        $plugins = SysPlugin::where('status', 1)->get()->toArray();
        return view($this->view_prefix.'add', ['plugins' => $plugins]);
    }

    /**
     * 新增数据
     * @param Request $request
     * @return array
     */
    public function store(Request $request){
        $data = $this->get_params($request, ['version','desc','pid','url','show_name']);
        $this->log(__CLASS__, __FUNCTION__, $request, "新增 插件版本");
        SysPluginVersion::create($data);
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * 编辑界面
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id){
        $pluginVersion = SysPluginVersion::where('id', $id)->first();
        $plugin = SysPlugin::where('id', $pluginVersion->pid)->first();
        return view($this->view_prefix.'edit', ['pluginVersion' => $pluginVersion, 'plugin' => $plugin]);
    }

    /**
     * 更新数据
     * @param Request $request
     * @param $id
     * @return array
     */
    public function update(Request $request){
        $data = $this->get_params($request, ['version','desc','pid','url','show_name','id',['status',0]]);
        $result = SysPluginVersion::where('id', $data['id'])->update($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "修改 插件版本");
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
        SysPluginVersion::destroy($ids);
        $this->log(__CLASS__, __FUNCTION__, $request, "删除 插件版本");
        return $this->ajax_return('200', '操作成功');
    }

    public function upload(Request $request){
        $file = $request->file('file');
        $name = $file->getClientOriginalName();
        $support_file_suffix = ['zip','ZIP'];
        $ext = pathinfo($name)['extension'];
        if(!in_array($ext, $support_file_suffix)) {
            return $this->ajax_return(500, "禁止上传的文件格式");
        }
        $core_path = 'plugins/';
        $dir = storage_path('app/'.$core_path);
        $filename = $name;
        $file->move($dir, $filename);
        $real_path = $core_path . $filename;
        return $this->ajax_return(200,'success', [
            'name' => $name,
            'url' =>  $real_path
        ]);
    }

    public function show(){

    }
}