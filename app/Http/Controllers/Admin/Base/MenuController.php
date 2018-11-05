<?php

namespace App\Http\Controllers\Admin\Base;

use Illuminate\Http\Request;
use App\Models\Admin\Base\SysMenu;
use App\Http\Controllers\Base\AdminController as AdminBaseController;

/**
 * MenuController
 * @author lay
 * @since 2018-10-25
 */
class MenuController extends AdminBaseController
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
        $menuList = SysMenu::where('is_use', 1)
                    ->get()
                    ->toArray();

        $data = [];
        foreach($menuList as $key => &$menus){
            if(0 != $menus['parent_id']){
                $data[$menus['parent_id']][] = $menus;
                unset($menuList[$key]);
            }
        }
        foreach($menuList as $key => &$menus){
            if(!empty($data[$menus['id']])){
                $menus['child'] = $data[$menus['id']];
            }
        }
        return view('admin.base.menu.index', ['menuList' => $menuList]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menuList = SysMenu::where('is_use', 1)
                    ->get()
                    ->toArray();

        $data = [];
        foreach($menuList as $key => &$menus){
            if(0 != $menus['parent_id']){
                $data[$menus['parent_id']][] = $menus;
                unset($menuList[$key]);
            }
        }
        foreach($menuList as $key => &$menus){
            if(!empty($data[$menus['id']])){
                $menus['child'] = $data[$menus['id']];
            }
        }
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
