<?php
namespace App\Http\Controllers\Admin\Base\Plugin;

use App\Models\Admin\Admin\SysAdmin;
use App\Models\Admin\Base\Plugin\SysPlugin;
use App\Models\Admin\Func\Plugin\RpaPluginApply;
use App\Models\Admin\Base\SysApiUser AS User;
use Illuminate\Http\Request;

/**
 * 用户申请列表
 * Class DownloadController
 * @package App\Http\Controllers\Admin\Base\Plugin
 */
class ApplyController extends BaseController
{

    private $view_prefix = "admin.base.plugin.apply.";

    /**
     * 插件申请页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 插件申请 页");
        return view($this->view_prefix.'index');
    }

    /**
     * 列表数据
     * @param Request $request
     * @return mixed
     */
    public function pagination(Request $request){
        $selectInfo = $this->get_params($request, ['status']);
        $condition = $this->getPagingList($selectInfo, ['status' => '=']);
        $rows = $request->rows;
        $order = ($request->sort ?? 'id');
        $sort = 'asc';
        $result = RpaPluginApply::where($condition)
            ->orderBy($order, $sort)
            ->paginate($rows);
        foreach ($result as &$v) {
            $v->pluginName = SysPlugin::where('id', $v->pid)->first()->name;
            $v->apply = SysAdmin::where('id', $v->uid)->first()->realName;
        }
        return $result;
    }

    /**
     * 编辑界面
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id){
        $apply = RpaPluginApply::where('id', $id)->first();
        $apply->pluginName = SysPlugin::where('id', $apply->pid)->first()->name;
        $apply->applyName = SysAdmin::where('id', $apply->uid)->first()->realName;
        return view($this->view_prefix.'edit', ['apply' => $apply]);
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
        return $this->ajax_return(200, '操作成功！');
    }

    /**
     * 确认
     * @param Request $request
     * @return array
     */
    public function confirm(Request $request){
        $id = $request->id;
        $status = $request->status;
        $confirm = auth()->guard('admin')->user()->realName;
        $result = RpaPluginApply::where('id', $id)->first();
        if($result->status != 1) {
            return $this->ajax_return(500, '该申请状态无法被改变'.$result->status);
        }
        $uid = $result->uid;
        $admin = SysAdmin::where('id', $uid)->first();
        $adminName = $admin->name;
        $result->status = $status;
        $result->confirm = $confirm;
        $result->confirm_time = date('Y-m-d H:i:s');
        $result->save();
        $ext = '@BrowserExt.com';
        $email = $adminName.$ext;
        $user = User::where('email', $email)->first();
        if(!$user) {
            User::create([
                'name' => $admin->realName,
                'email' => $email,
                'password' => createPassword($adminName)
            ]);
        }
        return $this->ajax_return(200, '成功');
    }
}