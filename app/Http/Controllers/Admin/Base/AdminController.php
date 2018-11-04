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
        return view('admin.admins.index');
    } 

    /**
     * show
     */
    public function show(Request $request){
        return view('admin.base.admin');
    }

    /**
     * show
     */
    public function pagenation(Request $request){
        $perPage = 10;
        $result = SysAdmin::paginate($perPage);
        return $result;
    }
    
}
