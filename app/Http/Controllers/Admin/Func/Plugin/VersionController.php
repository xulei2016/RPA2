<?php
namespace App\Http\Controllers\Admin\Func\Plugin;

use App\Models\Admin\Base\Document\SysDocumentContent;
use App\Models\Admin\Base\Document\SysDocumentMenu;
use App\Models\Admin\Func\Plugin\RpaPlugin;
use App\Models\Admin\Func\Plugin\RpaPluginVersion;
use App\Models\Admin\Func\Plugin\RpaPluginDownload;
use Illuminate\Http\Request;

/**
 * 插件版本管理
 * Class VersionController
 * @package App\Http\Controllers\Admin\Base\Plugin
 */
class VersionController extends BaseController
{
    private $view_prefix = "admin.func.plugin.version.";

    /**
     * 列表页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request) {
        $this->log(__CLASS__, __FUNCTION__, $request, "插件版本 列表页");
        return view($this->view_prefix.'index', ['id' => 0]);
    }

    /**
     * 列表数据
     * @param Request $request
     * @return mixed
     */
    public function pagination(Request $request){
        $selectInfo = $this->get_params($request, ['version', 'name', 'pid']);
        $condition = $this->getPagingList($selectInfo, ['version'=>'like', 'name' => 'like', 'pid' => '=']);
        $rows = $request->rows;
        $order = ($request->sort ?? 'id');
        $sort = 'asc';
        $result = RpaPluginVersion::from('rpa_plugin_versions as spv')
            ->leftJoin('rpa_plugins as sp', 'spv.pid', '=', 'sp.id')
            ->where($condition)
            ->select(['spv.*','sp.name'])
            ->orderBy($order, $sort)
            ->paginate($rows);
        foreach($result as &$v) {
            $v->downloadCount = RpaPluginDownload::where('version_id', $v->id)->count();
        }    
        return $result;
    }

    /**
     * 新增页面
     * @param Request $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request){
        $plugins = RpaPlugin::where([
            ['status', '=', 1],
            ['id', '=', $request->id]
        ])->get()->toArray();
        return view($this->view_prefix.'add', ['plugins' => $plugins]);
    }

    /**
     * 新增数据
     * @param Request $request
     * @return array
     */
    public function store(Request $request){
        $data = $this->get_params($request, ['version','desc','pid','url','show_name','doc_id']);
        $this->log(__CLASS__, __FUNCTION__, $request, "新增 插件版本");
        RpaPluginVersion::create($data);
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * 编辑界面
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id){
        $pluginVersion = RpaPluginVersion::where('id', $id)->first();
        $plugin = RpaPlugin::where('id', $pluginVersion->pid)->first();
        $doc = SysDocumentContent::where('did', $pluginVersion->doc_id)->first();
        if($doc) $pluginVersion->docName = $doc->name;
        return view($this->view_prefix.'edit', ['pluginVersion' => $pluginVersion, 'plugin' => $plugin]);
    }

    /**
     * 更新数据
     * @param Request $request
     * @param $id
     * @return array
     */
    public function update(Request $request){
        $data = $this->get_params($request, ['version','desc','pid','url','show_name','id','doc_id',['status',0]]);
        $result = RpaPluginVersion::where('id', $data['id'])->update($data);
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
        $dir = base_path();
        foreach($ids as $id) {
            $version = RpaPluginVersion::where('id', $id)->first();
            $filename = $dir."/storage/app/".$version->url;
            unlink($filename);
        }
        RpaPluginVersion::destroy($ids);
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
        // $filename = $name;
        $filename = date('YmdHis').rand(1000,9999).'.'.$ext;
        $file->move($dir, $filename);
        $real_path = $core_path . $filename;
        return $this->ajax_return(200,'success', [
            'name' => $name,
            'url' =>  $real_path
        ]);
    }

    public function show(){

    }

    /**
     * @param Request $id
     * 根据插件id获取列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getByPlugin(Request $request, $id){
        $this->log(__CLASS__, __FUNCTION__, $request, "插件版本 列表页");
        return view($this->view_prefix.'index', ['id' => $id]);
    }

    /**
     * 查找文档
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchDoc(Request $request){
        $docList = SysDocumentMenu::get(['id','parent_id as pid','name'])->toArray();
        return view($this->view_prefix. 'searchDoc',  ['list' => $docList]);
    }
}