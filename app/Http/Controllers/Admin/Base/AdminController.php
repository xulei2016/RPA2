<?php

namespace App\Http\Controllers\Admin\Base;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Admin\Admin\SysAdmin;
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
        $admin = SysAdmin::all();
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
        $roles = Role::where('id','!=','1')->get();
        $info['roles'] = explode(',', $info['roles']);
        return view('admin.admin.edit', ['info' => $info, 'roles' => $roles]);
    }

    /**
     * create
     */
    public function create(Request $request){
        $roles = Role::where('id','!=','1')->get();
        return view('admin.admin.add', ['roles' => $roles]);
    }

    /**
     * store
     */
    public function store(Request $request){
        $data = $this->get_params($request, ['name','type','sex','phone','realName','desc','password','email','roleLists'], false);
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
        $data = $this->get_params($request, ['id','name','type','sex','phone','realName','desc','password','email','roleLists']);
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
}
