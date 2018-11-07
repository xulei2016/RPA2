<?php

namespace App\Http\Controllers\Admin\Base;

use Illuminate\Http\Request;
use App\Models\Admin\Admin\SysAdmin;
use App\Http\Controllers\Base\AdminController as AdminBasecontroller;

/**
 * AdminController
 * @author lay
 * @since 2018-10-25
 */
class AdminController extends AdminBasecontroller
{
    /** 
     * Create a new controller instance. 
     * 
     * @return void 
     */ 
    public function __construct() 
    { 
        parent::__construct();
    }

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
        return view('admin.admin.edit', ['info' => $info]);
    }

    /**
     * create
     */
    public function create(Request $request){
        return view('admin.admin.add');
    }

    /**
     * store
     */
    public function store(Request $request){
        $data = $this->get_params($request, ['name','type','sex','phone','realName','desc','password','email'], false);
        $data['password'] = bcrypt($data['password']);
        $result = SysAdmin::create($data);
        // $this->log(__CLASS__, __FUNCTION__, $request, "删除用户");
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * update
     */
    public function update(Request $request){
        $data = $this->get_params($request, ['id','name','type','sex','phone','realName','desc','password','email']);
        if(null == $data['password'] || '' == $data['password']){
            unset($data['password']);
        }else{
            $data['password'] = bcrypt($data['password']);
        }
        $result = SysAdmin::where('id', $data['id'])
                ->update($data);
        // $this->log(__CLASS__, __FUNCTION__, $request, "删除用户");
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * destroy
     */
    public function destroy(Request $request, $id){
        $result = SysAdmin::destroy($id);
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
        $conditions = $this->getPagingList($request->all(), ['name'=>'like', 'role'=>'=', 'status'=>'=']);
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder;
        $result = SysAdmin::where($conditions)
                ->orderBy($order, $sort)
                ->paginate($rows);
        return $result;
    }
    
}
