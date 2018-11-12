<?php

namespace App\Http\Controllers\Admin\Base;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Base\BaseAdminController;
use Excel;

/**
 * ROLE 角色管理
 * @author hsu lay
 * @since 2018/2
 */
class RoleController extends BaseAdminController
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.base.role.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.base.role.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->get_params($request, ['name','guard_name','type','desc'], false);
        $result = Role::create($data);
        // $this->log(__CLASS__, __FUNCTION__, $request, "添加菜单");
        return $this->ajax_return(200, '操作成功！');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = Role::where('id', $id)->first();
        return view('admin.base.role.edit', ['info' => $info]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $this->get_params($request, ['name','guard_name','type','desc','id'], false);
        $result = Role::where('id', $data['id'])
                ->update($data);
        // $this->log(__CLASS__, __FUNCTION__, $request, "更新菜单");
        return $this->ajax_return(200, '恭喜你，操作成功！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = Role::destroy($id);
        // $this->log(__CLASS__, __FUNCTION__, $request, "添加菜单");
        return $this->ajax_return(200, '操作成功！');
    }

    /**
     * pagenation
     */
    public function pagenation(Request $request){
        $rows = $request->rows;
        $param = $this->get_params($request, ['name', 'type']);
        $conditions = $this->getPagingList($param, ['name'=>'like', 'type'=>'=']);
        $result = Role::where($conditions)
                ->paginate($rows);
        return $result;
    }

    /**
     * getPermission
     */
    public function getPermission(Request $request, $id){
        $roles = Role::all();
        return view('admin.base.role.permission', ['roles' => $roles, 'id' => $id]);
    }

    /**
     * getCheckPermission
     */
    public function getCheckPermission(Request $request, $id){
        $roles = Permission::where('status','1')->get(['id','pid','name as desc','desc as name'])->toArray();
        $role = Role::find($id);
        foreach($roles as &$v){
            if($role->hasPermissionTo($v['desc'])){
                $v['open'] = true;
                $v['checked'] = true;
            }
        }
        return $this->ajax_return('200', '查询成功！', $roles);
    }

    /**
     * roleHasPermission
     */
    public function roleHasPermission(Request $request, $id){
        $data = $request->all();
        $permissions = [];
        $role = Role::find($id);
        $roleReponsitory = Permission::all('name');
        foreach($roleReponsitory as $roles){
            $role->revokePermissionTo($roles->name);
        }
        if(isset($data['data'])){
            foreach($data['data'] as $permission){
                array_push($permissions, $permission['desc']);
            }
            $role->givePermissionTo($permissions);
        }
        return $this->ajax_return('200', '操作成功！');
    }
    
    /**
     * export
     */
    public function export(Request $request){
        $param = $this->get_params($request, ['name', 'type', 'id']);
        $conditions = $this->getPagingList($param, ['name'=>'like', 'type'=>'=']);

        if(isset($param['id'])){
            $data = Role::where($conditions)->whereIn('id', explode(',',$param['id']))->get()->toArray();
        }else{
            $data = Role::where($conditions)->get()->toArray();
        }
        
        $cellData = [];
        $cellData[] = array_keys($data[0]);
        foreach($data as $k => $info){
            array_push($cellData, array_values($info));
        }
        Excel::create('角色表',function($excel) use ($cellData){
            $excel->sheet('信息库', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
}
