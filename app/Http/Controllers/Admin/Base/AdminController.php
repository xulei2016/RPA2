<?php

namespace App\Http\Controllers\Admin\Base;

use App\Http\Controllers\Admin\Base\Organization\DeptController;
use App\Models\Admin\Base\Organization\SysDept;
use App\Models\Admin\Base\Organization\SysDeptFunctional;
use App\Models\Admin\Base\Organization\SysDeptPost;
use App\Models\Admin\Base\Organization\SysDeptPostRelation;
use App\Models\Admin\Base\Organization\SysDeptRelation;
use Hash;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use App\Models\Admin\Admin\SysAdmin;
use App\Models\Admin\Admin\SysAdminGroup;
use App\Http\Controllers\Base\BaseAdminController;
use Excel;

/**
 * AdminController
 * @author lay
 * @since 2018-10-25
 */
class AdminController extends BaseAdminController
{

    // 
    public function index(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "用户 列表页");
        return view('admin.admin.index');
    }

    /**
     * show
     * @param Request $request
     * @return Factory|View
     */
    public function show(Request $request){
        return view('admin.admin.index');
    }

    /**
     * 获取个人信息
     * @param Request $request
     * @return array
     */
    public function getById(Request $request){
        $info = SysAdmin::where('id', $request->id)->first()->toArray();
        $info['gender'] = $info['sex'] == 2?'女':'男';
        $department = SysDept::where('id', $info['dept_id'])->first();
        $info['department'] = $department?$department->name:'暂无';
        $deptRelationList = SysDeptRelation::where('admin_id', $info['id'])->get();  //员工有的岗位

        $postArr = [];
        foreach ($deptRelationList as &$v) {
            $deptPostRelation = SysDeptPostRelation::where('id', $v->post_relation_id)->first();
            $postArr[] = $deptPostRelation->fullname;
        }
        $info['post'] = $postArr?implode(',', $postArr):'暂无';
        switch ($info['status']) {
            case 1:
                $statusName = '正常';break;
            case 2:
                $statusName = '离职';break;
            case 3:
                $statusName = '冻结';break;
            case 4:
                $statusName = '注销';break;
            default:
                $statusName = '正常';
        }
        $info['statusName'] = $statusName;
        return $this->ajax_return(200, '成功', $info);
    }

    /**
     * edit
     * @param Request $request
     * @param $id
     * @return Factory|View
     */
    public function edit(Request $request, $id){
        $info = SysAdmin::where('id', $id)->first();
        $groupList = SysAdminGroup::all();
        $roles = Role::where('id','!=','1')->get();
        $info['roleLists'] = explode(',', $info['roleLists']);
        $departmentList = (new DeptController())->getTree();
        $deptRelationList = SysDeptRelation::where('admin_id', $info->id)->get();  //员工有的岗位
        $deptPostRelationList = SysDeptPostRelation::where('dept_id', $info->dept_id)->get(); // 部门有的岗位
        $postArr = [];
        foreach ($deptRelationList as &$v) {
            $deptPostRelation = SysDeptPostRelation::where('id', $v->post_relation_id)->first();
            $postArr[] = $deptPostRelation->id;
        }
        $info['posts'] = $postArr;
        $leader = [];
        if($info['leader_id']) {
            $leader = SysAdmin::where('id', $info['leader_id'])->first();
        }
        $functionalList = SysDeptFunctional::get();
        $department = SysDept::where('id', $info->dept_id)->first();
        return view('admin.admin.edit', [
            'info' => $info,
            'roles' => $roles,
            'groupList' => $groupList,
            'departmentList' => $departmentList,
            'functionalList' => $functionalList,
            'department' => $department,
            'deptPostRelationList' => $deptPostRelationList,
            'leader' => $leader
        ]);
    }

    /**
     * create
     * @param Request $request
     * @return Factory|View
     */
    public function create(Request $request){
        //客户分组
        $groupList = SysAdminGroup::all();
        $roles = Role::where('id','!=','1')->get();
        $deptId = $request->dept_id;
        $department = [];
        $postList = [];
        if($deptId) {
            $department = SysDept::where('id', $deptId)->first();
            $postList = SysDeptPostRelation::where('dept_id', $deptId)->get();
        }
        $functionalList = SysDeptFunctional::get();
        $departmentList = (new DeptController())->getTree();
        return view('admin.admin.add',
            [
                'roles' => $roles,
                'groupList' => $groupList,
                'departmentList' => $departmentList,
                'functionalList' => $functionalList,
                'department' => $department,
                'postList' => $postList,
            ]);
    }

    /**
     * store
     * @param Request $request
     * @return array|bool
     */
    public function store(Request $request){
        $data = $this->get_params($request, ['name',['type',0],['sex',2],'phone','realName','desc','password','email','roleLists','groupID','dept_id','posts', 'func_id','leader_id']);
        $roles = $data['roleLists'];
        $posts = $data['posts'];

        $data['roleLists'] = implode(',', $data['roleLists']);
        $data['posts'] = implode(',', $data['posts']);

        //密码强度检测
        $result = self::check_pwd($data['password']);
        if(isset($result['code'])){
            return $result;
        };
        $data['password'] = bcrypt($data['password']);
        $result = SysAdmin::create($data);

        foreach ($posts as $v) {
            SysDeptRelation::create([
                'admin_id' => $result->id,
                'post_relation_id' => $v
            ]);
        }
        $this->uploadPostIds($data['dept_id']);
        //同步角色
        $user = SysAdmin::find($result->id)->syncRoles($roles);

        $this->log(__CLASS__, __FUNCTION__, $request, "添加 用户");
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * update
     * @param Request $request
     * @return array|bool
     */
    public function update(Request $request){
        $data = $this->get_params($request, ['id','name',['type',0],['sex',2],'phone','realName','desc','password','email','roleLists','groupID','dept_id', 'posts', 'func_id','leader_id']);
        $roles = $data['roleLists'];
        $posts = $data['posts'];
        if(null == $data['password'] || '' == $data['password']){
            unset($data['password']);
        }else{
            //密码强度检测
            $result = self::check_pwd($data['password']);
            if(isset($result['code'])){
                return $result;
            };
            $data['password'] = bcrypt($data['password']);
        }
        $data['roleLists'] = implode(',', $data['roleLists']);
        $data['posts'] = implode(',', $data['posts']);
        $result = SysAdmin::where('id', $data['id'])->update($data);
        SysDeptRelation::where([
            'admin_id' => $data['id'],
        ])->delete();
        foreach ($posts as $v) {
            SysDeptRelation::create([
                'admin_id' => $data['id'],
                'post_relation_id' => $v
            ]);
        }
        $this->uploadPostIds($data['dept_id']);

        //同步角色
        $user = SysAdmin::find($data['id'])->syncRoles($roles);

        $this->log(__CLASS__, __FUNCTION__, $request, "更新 用户");
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * destroy
     * @param Request $request
     * @param $ids
     * @return array
     */
    public function destroy(Request $request, $ids){
        $ids = explode(',', $ids);
        if(in_array(1, $ids)){
            return $this->ajax_return('500', '操作失败！包含保护项！！');
        }

        $result = SysAdmin::destroy($ids);
        $this->log(__CLASS__, __FUNCTION__, $request, "删除 用户");
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * destroyAll
     * @param Request $request
     * @return Factory|View
     */
    public function destroyAll(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "删除所有 用户");
        return view('admin.admin.index');
    }

    /**
     * show
     * @param Request $request
     * @return
     */
    public function pagenation(Request $request){
        $rows = $request->rows;
        $data = $this->get_params($request, ['name','realName']);
        $conditions = $this->getPagingList($data, ['name'=>'like', 'realName'=>'like']);
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder;
        $result = SysAdmin::where($conditions)
            ->leftJoin('sys_admin_groups', 'sys_admins.groupID', '=', 'sys_admin_groups.id')
            ->select(['sys_admins.*', 'sys_admin_groups.group'])
            ->orderBy($order, $sort)
            ->paginate($rows);
        // foreach ($result as &$item) {
        //     $roleLists = [];
        //     foreach (explode(',', $item->roleLists) as $v) {
        //         $role = SysRole::where('name', $v)->first();
        //         if($role) $roleLists[] = $role->desc;
        //     }
        //     $item->roleLists = implode(',', $roleLists);
        // }
        return $result;
    }

    /**
     * export
     * @param Request $request
     */
    public function export(Request $request){
        $param = $this->get_params($request, ['name', 'type', 'id']);
        $conditions = $this->getPagingList($param, ['name'=>'like', 'type'=>'=']);

        if(isset($param['id'])){
            $data = SysAdmin::where($conditions)->whereIn('id', explode(',',$param['id']))->get()->toArray();
        }else{
            $data = SysAdmin::where($conditions)->get()->toArray();
        }

        $cellData = [];
        $cellData[] = array_keys($data[0]);
        foreach($data as $k => $info){
            array_push($cellData, array_values($info));
        }
        Excel::create('管理员信息表',function($excel) use ($cellData){
            $excel->sheet('信息库', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }

    ////////////////////////////////////////////////////////////个人中心///////////////////////////////////////////////

    /**
     * 个人中心
     * @param Request $request
     * @return Factory|View
     */
    public function userCenter(Request $request){
        $info = auth()->guard()->user();
        $this->log(__CLASS__, __FUNCTION__, $request, "个人中心");
        return view('admin.base.admin.index', ['info' => $info]);
    }

    /**
     * 信息修改
     * @param Request $request
     * @return array|bool
     */
    public function updateUser(Request $request){
        //表单类型
        switch($request->type){
            case 'settings':
                return self::changeInfo($request);
                break;
            case 'rpasetting':
                return self::rpasetting($request);
                break;
            case 'changePWD':
                return self::changePWD($request);
                break;
            case 'file':
                return self::changeHeadImg($request);
                break;
            case 'another':
                return self::another($request);
                break;
            default:
                return $this->ajax_return('500', '操作失败，未匹配到操作类型！！');
                break;
        }
    }

    /**
     * rpasetting
     * @param Request $request
     * @return array
     */
    private function rpasetting(Request $request){
        $data = $this->get_params($request, ['accept_mes_info', 'accept_mes_type']);

        $user = session('sys_admin');
        if(isset($data['accept_mes_info'])){
            if(1 == $data['accept_mes_type']){
                if(!$user['phone']){
                    $error_info = '请先完善手机号！';
                }
            }elseif(2 == $data['accept_mes_type']){
                if(!$user['email']){
                    $error_info = '请先完善邮箱！';
                }
            }elseif(3 == $data['accept_mes_type']){
                if(!$user['email'] || !$user['phone']){
                    $error_info = '请先完善邮箱和手机号！';
                }
            }
            if(isset($error_info)){
                return [ 'code' => '500', 'info' => $error_info];
            }
        }
        $this->log(__CLASS__, __FUNCTION__, $request, "修改 rpa 设置");
        SysAdmin::where('id', $user['id'])->update($data);
        return [ 'code' => '200', 'info' => '设置成功！！'];
    }

    /**
     * 修改密码
     * @param Request $request
     * @return array|bool
     */
    private function changePWD(Request $request){
        $data = $this->get_params($request, ['oriPWD', 'password', 'rePWD']);

        //原始密码检测
        if(self::check_ori_pwd($data['oriPWD'])){
            //两次密码检测
            if($data['password'] != $data['rePWD']){
                return [ 'code' => '500', 'info' => '两次填写的密码不一致！！！'];
            }
            //密码强度检测
            $result = self::check_pwd($data['password']);
            if(isset($result['code'])){
                return $result;
            };

            //修改密码
            $this->log(__CLASS__, __FUNCTION__, $request, "更新 密码 信息");
            SysAdmin::where('id', auth()->guard('admin')->user()->id)->update([
                'password' => bcrypt($data['password']),
                'first_login' => 0
            ]);
            return [ 'code' => '200', 'info' => '修改成功！！'];
        }else{
            return [ 'code' => '500', 'info' => '原始密码错误！！！'];
        }
    }

    /**
     * 其他设置
     * @param Request $request
     * @return array
     */
    private function another(Request $request){
        $data = $this->get_params($request, [['single_login', 0], ['login_protected', 0]]);
        $user = session('sys_admin');
        $this->log(__CLASS__, __FUNCTION__, $request, "修改 rpa 设置");
        SysAdmin::where('id', $user['id'])->update($data);
        return [ 'code' => '200', 'info' => '设置成功！！'];
    }

    /**
     * 初次登陆修改密码页面
     * @param Request $request
     * @return Factory|View
     */
    public function firstLogin(Request $request){
        return view("admin.base.admin.first");
    }

    /**
     * 清空错误次数
     * @param Request $request
     * @return array
     */
    public function clearCount(Request $request){
        $admin = SysAdmin::where('id', $request->id)->first();
        if(!$admin) return $this->ajax_return(500, '未找到该用户');
        $admin->error_count = 0;
        $result = $admin->save();
        if($result) {
            return $this->ajax_return(200, '修改成功');
        } else {
            return $this->ajax_return(500, '修改失败');
        }
    }

    /**
     * 个人信息修改
     * @param Request $request
     * @return array
     */
    private function changeInfo(Request $request){
        $data = $this->get_params($request, ['realName', 'phone', 'email', 'desc']);

        //修改密码
        $this->log(__CLASS__, __FUNCTION__, $request, "更新 个人信息 信息");
        SysAdmin::where('id', auth()->guard('admin')->user()->id)->update($data);
        return [ 'code' => '200', 'info' => '修改成功！！'];
    }

    /**
     * 修改头像
     * @param Request $request
     * @return array
     */
    private function changeHeadImg(Request $request){
        //显示的属性更多
        $fileCharater = $request->file('file');

        if ($fileCharater->isValid()) { //括号里面的是必须加的哦
            //如果括号里面的不加上的话，下面的方法也无法调用的

            $ext = $fileCharater->extension();
            $store_result = $fileCharater->store('/images/admin/headImg');
        }
        //修改个人信息
        $id = auth()->guard('admin')->user()->id;

        $head_img = auth()->guard('admin')->user()->head_img;

        $this->log(__CLASS__, __FUNCTION__, $request, "更新 个人头像 信息");
        $result = SysAdmin::where('id', $id)->update(['head_img' => $store_result]);

        //删除原图
        $this->unlinkImg('/'.$head_img);

        return [ 'code' => '200', 'info' => '上传成功！！'];
    }

    /**
     * 检查原密码
     * @param $pwd
     * @return bool
     */
    private function check_ori_pwd($pwd){
        $user = auth()->guard('admin')->user();
        if (Hash::check($pwd,$user->password)) {
            return true;
        }
        return false;
    }

    /**
     * 密码强度验证
     * 不包含当前用户名
     * 不小于8位
     * 数字/字母/大小写字母/特殊字符
     * @param $str
     * @return array|bool
     */
    private function check_pwd($str){
        $score = 0;
        $array = [
            "/[0-9]+/",
            "/[a-z]+/",
            "/[A-Z]+/",
            "/[_|\-|+|=|*|!|@|#|$|%|^|&|(|)]+/"
        ];
        if(strlen($str) < 8){
            return ['code' => 500, 'info' => '密码长度至少8位。'];
        }
        $user = auth()->guard('admin')->user();
        $pwd = $user->pwd;
        if (Hash::check($pwd,$str)) {
            return ['code' => 500, 'info' => '请勿与原始密码一致。'];
        }
        if(substr_count($str,$user->name)){
            return ['code' => 500, 'info' => '请勿包含当前用户名'];
        }
        foreach($array as $key){
            if(preg_match($key,$str)){
                $score ++;
            }
        }
        if($score < 3){
            return ['code' => 500, 'info' => '密码强度不足，请确认至少需要数字、字母、字母大写小、特殊字符中的三个及以上组合。'];
        }
        return true;
    }



    /**
     * 更新部门下的岗位
     * @param $deptId
     */
    public function uploadPostIds($deptId){
        $dept = SysDept::where('id', $deptId)->first();
        $deptPostRelations = SysDeptPostRelation::where('dept_id', $deptId)->get(); // 部门岗位关系表
        $list = [];
        foreach ($deptPostRelations as $deptPostRelation) {
            $post = SysDeptPost::where('id', $deptPostRelation->post_id)->first();
            $deptRelations = SysDeptRelation::where('post_relation_id', $deptPostRelation->id)->get(['admin_id']);
            foreach ($deptRelations as $deptRelation) {
                $list[$post->unique_name][] = $deptRelation->admin_id;
            }
        }
        $dept->post_ids = json_encode($list);
        $dept->save();
    }
}
