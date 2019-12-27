<?php


namespace App\Http\Controllers\Admin\Base\Organization;


use App\Http\Controllers\base\BaseAdminController;
use App\Models\Admin\Base\Organization\SysDept;
use App\Models\Admin\Base\Organization\SysDeptPost;
use App\Models\Admin\Base\Organization\SysDeptPostRelation;
use Illuminate\Http\Request;

/**
 * 部门岗位关系
 * Class DeptPostRelationController
 * @package App\Http\Controllers\Admin\Base\Organization
 */
class DeptPostRelationController extends BaseAdminController
{
    private $view_prefix = "Admin.Base.Organization.DeptPostRelation.";

    /**
     * 新增页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request){
        if($request->dept_id) {
            $dept= SysDept::where('id', $request->dept_id)->first();
        } else {
            $dept = '';
        }
        $postList = SysDeptPost::orderBy('rank', 'asc')->get();
        $deptList = SysDept::get();
        return view($this->view_prefix.'add', [
            'dept' => $dept,
            'postList' => $postList,
            'deptList' => $deptList,
        ]);
    }

    /**
     * 保存
     * @param Request $request
     * @return array
     */
    public function store(Request $request){
        $data = $this->get_params($request, ['dept_id', 'post_id', 'duty', 'qualification', 'remark', 'fullname']);
        SysDeptPostRelation::create($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "添加-部门岗位关系");
        return $this->ajax_return(200, '成功');
    }

    /**
     * 更新页面
     * @param Request $request
     * @param $id
     */
    public function edit(Request $request, $id){}

    /**
     * 更新
     * @param Request $request
     */
    public function update(Request $request) {

    }

    /**
     * 根据部门id获取岗位
     * @param Request $request
     * @return array
     */
    public function getByDeptId(Request $request) {
        $list = SysDeptPostRelation::where('dept_id', $request->dept_id)->get()->toArray();
        foreach ($list as $k => $v) {
            $list[$k]['postName'] = SysDeptPost::where('id', $v['post_id'])->first()->name;
        }
        return $this->ajax_return(200, '操作成功', $list);
    }
}