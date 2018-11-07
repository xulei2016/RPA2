<?php

namespace App\Http\Controllers\Admin\Base;

use Illuminate\Http\Request;
use App\Models\Admin\Base\SysRole;
use App\Http\Controllers\Base\AdminController as AdminBaseController;

/**
 * ROLE 角色管理
 * @author hsu lay
 * @since 2018/2
 */
class RoleController extends AdminBaseController
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
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menuList = $this->AllMenus();
        return view('admin.base.menu.index', ['menuList' => $menuList]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menuList = $this->AllMenus();
        return view('admin.base.menu.add', ['menuList' => $menuList]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->get_params($request, ['parent_id','title','uri','icon','order','role','permission'], false);
        $result = SysMenu::create($data);
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
        $menuList = $this->AllMenus();
        $menu = SysMenu::where('id', $id)->first();
        return view('admin.base.menu.edit', ['menuList' => $menuList, 'menuInfo' => $menu]);
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
        $data = $this->get_params($request, ['parent_id','title','uri','icon','order','id'], false);
        $result = SysMenu::where('id', $data['id'])
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
        $result = SysMenu::destroy($id);
        // $this->log(__CLASS__, __FUNCTION__, $request, "添加菜单");
        return $this->ajax_return(200, '操作成功！');
    }

    /**
     * pagenation
     */
    public function pagenation(Request $request){
        $rows = $request->rows;
        $conditions = $this->getPagingList($request->all(), ['name'=>'like', 'role'=>'=', 'status'=>'=']);
        $result = SysAdmin::where($conditions)
                ->paginate($rows);
        return $result;
    }
}
