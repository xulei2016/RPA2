<?php


namespace App\Http\Controllers\Admin\Base\Organization;



use App\Http\Controllers\base\BaseAdminController;
use App\Models\Admin\Admin\SysAdmin;
use App\Models\Admin\Base\Organization\SysDept;
use App\Models\Admin\Base\Organization\SysDeptPost;
use App\Models\Admin\Base\Organization\SysDeptPostRelation;
use App\Models\Admin\Base\Organization\SysDeptRelation;
use Illuminate\Http\Request;


/**
 * 公司组织结构--部门
 * Class DepartmentController
 * @package App\Http\Controllers\Admin\Base\Organization
 */
class DeptController extends BaseAdminController
{

    public $view_prefix = "Admin.Base.Organization.Dept."; // view 前缀

    public function index(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "部门-首页");
        return view($this->view_prefix.'index');
    }

    /**
     * 获取所有菜单
     * @param Request $request
     * @return array
     */
    public function getMenus(Request $request){
        $list = SysDept::getAdmins();
        return $this->ajax_return(200, '成功', $list);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchAdmins(Request $request) {
        $list = SysDept::getAdmins();
        return view($this->view_prefix.'searchAdmin', ['list' => $list]);
    }
    /**
     * @param Request $request
     */
    public function show(Request $request){}

    /**
     * 获取树状结构
     * @return array
     */
    public function getTree(){
        $list = $this->getRealTree(0);
        $newList = $this->getListByTree($list, []);
        return $newList;
    }

    /**
     * 根据树生成一维数组
     * @param $list
     * @param $newList
     * @param int $level
     * @return array
     */
    public function getListByTree($list, $newList, $level = 0){
        foreach ($list as $k => $v) {
            $newList[] = [
                'id' => $v['id'],
                'name' => $v['name'],
                'level' => $level
            ];
            if($v['child']) {
                $l = $level+1;
                $newList = $this->getListByTree($v['child'], $newList, $l);
            }
        }
        return $newList;
    }

    /**
     * 根据结构树生成 select html
     * @param $list
     * @param $html
     * @param int $level
     * @return string
     */
    public function getHtmlByTree($list, $html, $level = 0){
        foreach ($list as $k => $v) {
            $html .= "<option value='{$v['id']}'>".str_repeat('--', $level)."{$v['name']}</option>";
            if($v['child']) {
                $l = $level+1;
                $html = $this->getHtmlByTree($v['child'], $html, $l);
            }
        }
        return $html;
    }

    /**
     * 获得真实树结构
     * @param $parentId
     * @param bool $flag
     * @param int $level
     * @return array
     */
    public function getRealTree($parentId, $flag = false, $level = 0){
        $list = SysDept::where('pid', $parentId)->select(['id','name','pid'])->get()->toArray();
        if(!$list) return [];
        foreach ($list as $k => $v) {
            $list[$k]['child'] = $this->getRealTree($v['id']);
        }
        return $list;
    }

    /**
     * 添加部门
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $data = $this->get_params($request, ['name','pid']);
        $dept = SysDept::where('id', $data['pid'])->first();
        $result = SysDept::create($data);
        $result->path =  $dept->path.$result->id.',';
        $result->save();
        $this->log(__CLASS__, __FUNCTION__, $request, "添加部门结点");
        return $this->ajax_return('200', '操作成功！', $result);
    }

    /**
     * 修改
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id){
        $info = SysDept::where('id', $id)->first();
        $admins = SysAdmin::where('dept_id', $id)->get();
        $leader = SysAdmin::where('id', $info->leader_id)->first();
        return view($this->view_prefix.'edit', [
            'admins' => $admins,
            'info' => $info,
            'leader' => $leader
        ]);
    }

    /**
     * 修改部门名称
     * @param Request $request
     * @param $id
     * @return array
     */
    public function update(Request $request, $id){
        $data = $this->get_params($request, ['name','pid','leader_id','manager_id']);
        $result = SysDept::where('id', $id)->update($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "修改部门结点");
        return $this->ajax_return('200', '操作成功！', $result);
    }

    /**
     * 删除节点
     * @param Request $request
     * @param $id
     * @return array
     */
    public function destroy(Request $request, $id)
    {
        $hasChild = SysDept::where('pid',$id)->first();
        if($hasChild){
            return $this->ajax_return('500', '操作失败，该节点拥有子节点！');
        }
        SysDept::where('id',$id)->delete();
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * 根据部门id获取详细信息
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail(Request $request){
        $deptId = $request->id;
        //人员信息
        $adminList = SysAdmin::where('dept_id', $deptId)->get();
        foreach ($adminList as &$v) {
            $pName = [];
            $deptRelations = SysDeptRelation::where('admin_id', $v->id)->get();
            foreach ($deptRelations as $deptRelation) {
                $deptPostRelation = SysDeptPostRelation::where('id', $deptRelation->post_relation_id)->first();
                $pName[] = $deptPostRelation->fullname;
            }
            $v->post = $pName?implode('、', $pName):"暂无";
        }
        //部门信息
        $dept = SysDept::where('id', $deptId)->first();
        //上级部门
        $pDept = SysDept::where('id', $dept->pid)->first();
        $dept->pName = $pDept?$pDept->name:'暂无';
        //部门负责人
        $manager = SysAdmin::where('id', $dept->manager_id)->first();
        $dept->manager = $manager?$manager->realName:'暂无';
        //部门分管领导
        $leader =  SysAdmin::where('id', $dept->leader_id)->first();
        $dept->leader = $leader?$leader->realName:'暂无';
        //部门人数
        $dept->count = SysAdmin::where('dept_id', $deptId)->count();

        //下属部门
        $deptList = SysDept::where('pid', $deptId)->get();
        foreach ($deptList as &$v) {
            $v->count = SysAdmin::where('dept_id', $v->id)->count();
        }

        //岗位
        $postList = SysDeptPostRelation::where('dept_id', $deptId)->get();
        foreach ($postList as &$v) {
            $v->name = SysDeptPost::where('id', $v->post_id)->first()->name;
        }

        return view($this->view_prefix.'detail', [
            'adminList' => $adminList,
            'dept' => $dept,
            'deptList' => $deptList,
            'postList' => $postList
        ]);
    }

    /**
     * 根据部门获取详细
     * @param Request $request
     * @return array
     */
    public function getDetailById(Request $request){
        $deptId = $request->id;
        // 部门信息
        $dept = SysDept::where('id', $deptId)->first()->toArray();
        $pDept = SysDept::where('id', $dept->pid)->first();
        $dept['pName'] = $pDept->name;
        $admins = SysAdmin::where('dept_id', $deptId)->get();
        $posts = SysDeptPost::where('dept_id', $deptId)->orderBy('rank', 'asc')->get()->toArray();
        $postList[0] = '暂无';
        foreach ($posts as $v) {
            $postList[$v['id']] = $v['name'];
        }
        $newAdmins = [];
        foreach ($admins as $v) {
            $newAdmins[] = [
                'id' => $v->id,
                'name' => $v->name,
                'realName' => $v->realName,
                'post' => $postList[$v->post_id]
            ];
        }
        return $this->ajax_return(200, '成功', [
            'admins' => $newAdmins,
            'posts' => $posts
        ]);
    }
}