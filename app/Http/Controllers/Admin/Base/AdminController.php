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
     * add
     */
    public function add(Request $request){
        return view('admin.admin.add');
    }

    /**
     * insert
     */
    public function insert(Request $request){
        $data = $this->get_params($request, ['name','type','sex','phone','realName','desc','password','email'], false);
        $result = SysAdmin::create($data);
        // $this->log(__CLASS__, __FUNCTION__, $request, "删除用户");
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * delete
     */
    public function delete(Request $request){
        $id = $request->id;
        $result = SysAdmin::destory($id);
        // $this->log(__CLASS__, __FUNCTION__, $request, "删除用户");
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * deleteAll
     */
    public function deleteAll(Request $request){
        return view('admin.admin.index');
    }

    /**
     * show
     */
    public function pagenation(Request $request){
        $rows = $request->rows;
        $conditions = $this->getPagingList($request->all(), ['name'=>'like', 'role'=>'=', 'status'=>'=']);
        $result = SysAdmin::where($conditions)
                ->paginate($rows);
        return $result;
    }
    
}
