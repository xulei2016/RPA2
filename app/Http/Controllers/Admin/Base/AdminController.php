<?php

namespace App\Http\Controllers\Admin\Base;

use Hash;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Admin\Admin\SysAdmin;
use App\Models\Admin\Admin\SysAdminGroup;
use App\Http\Controllers\Base\BaseAdminController;
use Excel;

use App\user;

/**
 * AdminController
 * @author lay
 * @since 2018-10-25
 */
class AdminController extends BaseAdminController
{

    // 
    public function index() 
    { 
        return view('admin.admin.index');
    } 

    /**
     * show
     */
    public function show(Request $request){
        return view('admin.admin.index');
    }

    /**
     * edit
     */
    public function edit(Request $request, $id){
        $info = SysAdmin::where('id', $id)->first();
        $groupList = SysAdminGroup::all();
        $roles = Role::where('id','!=','1')->get();
        $info['roleLists'] = explode(',', $info['roleLists']);
        return view('admin.admin.edit', ['info' => $info, 'roles' => $roles, 'groupList' => $groupList]);
    }

    /**
     * create
     */
    public function create(Request $request){
        //客户分组
        $groupList = SysAdminGroup::all();
        $roles = Role::where('id','!=','1')->get();
        return view('admin.admin.add', ['roles' => $roles, 'groupList' => $groupList]);
    }

    /**
     * store
     */
    public function store(Request $request){
        $data = $this->get_params($request, ['name','type','sex','phone','realName','desc','password','email','roleLists','groupID'], false);
        $roles = $data['roleLists'];
        $data['roleLists'] = implode(',', $data['roleLists']);
        $data['password'] = bcrypt($data['password']);
        $result = SysAdmin::create($data);

        //同步角色
        $user = SysAdmin::find($result->id)->syncRoles($roles);

        $this->log(__CLASS__, __FUNCTION__, $request, "添加用户");
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * update
     */
    public function update(Request $request){
        $data = $this->get_params($request, ['id','name','type','sex','phone','realName','desc','password','email','roleLists','groupID']);
        $roles = $data['roleLists'];
        if(null == $data['password'] || '' == $data['password']){
            unset($data['password']);
        }else{
            $data['password'] = bcrypt($data['password']);
        }
        $data['roleLists'] = implode(',', $data['roleLists']);
        $result = SysAdmin::where('id', $data['id'])->update($data);

        //同步角色
        $user = SysAdmin::find($data['id'])->syncRoles($roles);

        $this->log(__CLASS__, __FUNCTION__, $request, "更新用户");
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * destroy
     */
    public function destroy(Request $request, $ids){
        $ids = explode(',', $ids);
        if(in_array(1, $ids)){
            return $this->ajax_return('500', '操作失败！包含保护项！！');
        }
        
        $result = SysAdmin::destroy($ids);
        // $this->log(__CLASS__, __FUNCTION__, $request, "删除用户");
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * destroyAll
     */
    public function destroyAll(Request $request){
        return view('admin.admin.index');
    }

    /**
     * show
     */
    public function pagenation(Request $request){
        $rows = $request->rows;
        $data = $this->get_params($request, ['name','role','type']);
        $conditions = $this->getPagingList($data, ['name'=>'like', 'role'=>'=', 'type'=>'=']);
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder;
        $result = SysAdmin::where($conditions)
                ->leftJoin('sys_admin_groups', 'sys_admins.groupID', '=', 'sys_admin_groups.id')
                ->select(['sys_admins.*', 'sys_admin_groups.group'])
                ->orderBy($order, $sort)
                ->paginate($rows);
        return $result;
    }

    /**
     * export
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
     */
    public function userCenter(){
        $info = auth()->guard()->user();
        return view('admin.base.admin.index', ['info' => $info]);
    }

    /**
     * 信息修改
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
            default:
                return $this->ajax_return('500', '操作失败，未匹配到操作类型！！');
                break;
        }
    } 

    /**
     * rpasetting
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
            SysAdmin::where('id', auth()->guard('admin')->user()->id)->update(['password'=>bcrypt($data['password'])]);
            return [ 'code' => '200', 'info' => '修改成功！！'];
        }else{
            return [ 'code' => '500', 'info' => '原始密码错误！！！'];
        }
    }

    /**
     * 个人信息修改
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
}
