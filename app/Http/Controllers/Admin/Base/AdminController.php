<?php

namespace App\Http\Controllers\Admin\Base;

use Illuminate\Http\Request;
use App\Http\Controllers\Base\AdminController as admin;

/**
 * AdminController
 * @author lay
 * @since 2018-10-25
 */
class AdminController extends admin
{
    /** 
     * Create a new controller instance. 
     * 
     * @return void 
     */ 
    public function __construct() 
    { 
    }

    // 
    public function index() 
    { 
        return view('admin.base.admin.index');
    } 

    /**
     * show
     */
    public function show(Request $request){
        return view('admin.base.admin');
    }
    
}
