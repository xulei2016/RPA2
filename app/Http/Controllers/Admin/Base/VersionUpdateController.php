<?php

namespace App\Http\Controllers\Admin\Base;


use App\Http\Controllers\base\BaseAdminController;
use App\Models\Admin\Base\SysConfig;
use App\Models\Admin\Base\SysVersionUpdate;
use Illuminate\Http\Request;
use Cache;

/**
 * 版本更新控制器
 * Class VersionUpdateController
 * @package App\Http\Controllers\Admin\Base
 */
class VersionUpdateController extends BaseAdminController
{

    private $view_prefix = 'admin.base.versionUpdate.';

    private $typeList = [
        '1' => '正常更新',
        '2' => '版本升级',
        '3' => '紧急维护',
    ];

    /**
     * 列表页
     * @param Request $request
     */
    public function index(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "版本更新 列表页");
        return view($this->view_prefix.'index');
    }

    /**
     * 列表数据
     * @param Request $request
     * @return
     */
    public function pagination(Request $request) {
        $selectInfo = $this->get_params($request, ['desc', 'type']);
        $condition = $this->getPagingList($selectInfo, ['desc'=>'like', 'type' => '=']);
        $rows = $request->rows;
        $order = ($request->sort ?? 'id');
        $sort = ($request->sort ?? 'desc');
        $result = SysVersionUpdate::where($condition)
            ->orderBy($order, $sort)
            ->paginate($rows);
        return $result;
    }

    /**
     * 首页数据
     * @param Request $request
     */
    public function indexData(Request $request){}

    /**
     * 新增页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request){
        $versionNumber = SysConfig::where('item_key', 'version_number')->first();
        $this->log(__CLASS__, __FUNCTION__, $request, "版本更新 新增页");
        return view($this->view_prefix.'add', ['typeList' => $this->typeList, 'versionNumber' => $versionNumber]);
    }

    /**
     * 保存
     * @param Request $request
     * @return array
     */
    public function store(Request $request){
        $post = $request->all();
        $post['created_by'] = auth()->guard('admin')->user()->realName;
        $versionNumber = $post['version_number'];
        unset($post['version_number']);
        $version = SysConfig::where('item_key', 'version_number')->first();
        if(!$versionNumber) {
            $versionArray = explode('.', $version->item_value);
            $versionArray[2] += 1;
            $versionNumber = implode('.', $versionArray);
        }
        $version->item_value = $versionNumber;
        $version->save();
        Cache::forget('sysConfigs');
        $result = SysVersionUpdate::create($post);
        if($result) {
            return $this->ajax_return(200, '操作成功');
        } else {
            return $this->ajax_return(500, '保存失败');
        }
    }

    /**
     * 修改页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id){
        $this->log(__CLASS__, __FUNCTION__, $request, "版本更新 修改页");
        $result = SysVersionUpdate::find($id);
        return view($this->view_prefix.'edit', ['info' => $result, 'typeList' => $this->typeList]);
    }


    /**
     * 更新
     * @param Request $request
     * @return array
     */
    public function update(Request $request) {
        $post = $request->all();
        $post['updated_by'] = auth()->guard('admin')->user()->realName;
        unset($post['_method']);
        $result = SysVersionUpdate::where('id', $request->id)->update($post);
        if($result) {
            return $this->ajax_return(200, '操作成功');
        } else {
            return $this->ajax_return(500, '保存失败');
        }
    }

    /**
     * 删除
     * @param Request $request
     * @return array
     */
    public function destroy(Request $request, $id){
        $this->log(__CLASS__, __FUNCTION__, $request, "版本更新 删除");
        $result = SysVersionUpdate::destroy($id);
        if($result) {
            return $this->ajax_return(200, '操作成功');
        } else {
            return $this->ajax_return(500, '保存失败');
        }
    }

    /**
     * 查看单个
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $id){
        $this->log(__CLASS__, __FUNCTION__, $request, "版本更新 查看单个");
        $info = SysVersionUpdate::where('id', $id)->first();
        return view($this->view_prefix.'show', ['info' => $info]);
    }
}