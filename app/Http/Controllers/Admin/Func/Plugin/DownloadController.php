<?php
namespace App\Http\Controllers\Admin\Func\Plugin;

use App\Models\Admin\Admin\SysAdmin;
use App\Models\Admin\Func\Plugin\RpaPlugin;
use App\Models\Admin\Func\Plugin\RpaPluginDownload;
use App\Models\Admin\Func\Plugin\RpaPluginVersion;
use Illuminate\Http\Request;

/**
 * 下载
 * Class DownloadController
 * @package App\Http\Controllers\Admin\Base\Plugin
 */
class DownloadController extends BaseController
{

    private $view_prefix = "admin.func.plugin.download.";

    /**
     * index页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "插件下载 列表页");
        return view($this->view_prefix.'index', ['pid' => $request->pid?:'']);
    }

    /**
     * 列表数据
     * @param Request $request
     * @return mixed
     */
    public function pagination (Request $request){
        $selectInfo = $this->get_params($request, ['plugin_id']);
        $condition = $this->getPagingList($selectInfo, ['plugin_id'=>'=']);
        $rows = $request->rows;
        $order = ($request->sort ?? 'id');
        $sort = 'desc';
        $result = RpaPluginDownload::where($condition)
            ->orderBy($order, $sort)
            ->paginate($rows);
        foreach ($result as &$v) {
            $v->name = SysAdmin::where('id', $v->uid)->first()->realName;
            $v->pluginName = RpaPlugin::where('id', $v->plugin_id)->first()->name;
            $v->version = RpaPluginVersion::where('id', $v->version_id)->first()->version;
        }
        return $result;
    }
}