<?php

namespace App\Http\Controllers\Admin\Base\Organization;

use App\Http\Controllers\base\BaseAdminController;
use App\Models\Admin\Base\Organization\SysDept;
use App\Models\Admin\Base\Organization\SysDeptPost;
use Illuminate\Http\Request;

/**
 * 公司组织结构--岗位
 * Class PostController
 * @package App\Http\Controllers\Admin\Base\Organization
 */
class PostController extends BaseAdminController
{
    public $view_prefix = "Admin.Base.Organization.Post."; // view 前缀

    //首页
    public function index(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "岗位-首页");
    }

    /**
     * 新增岗位页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request){
        $deptId = $request->dept_id;
        $dept = SysDept::where('id', $deptId)->first();
        return view($this->view_prefix. 'add', ['dept' => $dept]);
    }

    /**
     * 新增岗位
     * @param Request $request
     * @return array
     */
    public function store(Request $request){
        $data = $this->get_params($request, ['name', 'dept_id', 'rank']);
        $has = SysDeptPost::where([
            ['name', '=', $data['name']],
            ['dept_id', '=', $data['dept_id']],
        ])->first();
        if($has) {
            return $this->ajax_return(500, '该部门已经有相同名称的岗位');
        }
        $result = SysDeptPost::create($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "添加-岗位");
        return $this->ajax_return(200, '操作成功！', $result);
    }

    /**
     * 根据部门id获取岗位
     * @param Request $request
     * @return array
     */
    public function getPostsByDeptId(Request $request) {
        $list = SysDeptPost::where('dept_id', $request->dept_id)->get()->toArray();
        return $this->ajax_return(200, '操作成功', $list);
    }
}