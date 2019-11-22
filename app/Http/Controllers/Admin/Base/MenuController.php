<?php

namespace App\Http\Controllers\Admin\Base;

use Illuminate\Http\Request;
use App\Models\Admin\Base\SysMenu;
use App\Http\Controllers\Base\BaseAdminController;

use App\Models\Admin\Admin\SysAdmin;
use Spatie\Permission\Models\Role;

/**
 * MenuController
 * @author lay
 * @since 2018-10-25
 */
class MenuController extends BaseAdminController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $menuList = $this->AllMenus();
        $this->log(__CLASS__, __FUNCTION__, $request, "菜单 列表页");
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
        $data = $this->get_params($request, [['parent_id',0],'title','uri','icon','order','unique_name']);
        $result = SysMenu::create($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "添加 菜单");
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
        $data = $this->get_params($request, [['parent_id',0],'title','uri','icon','order','id','unique_name'], false);
        $result = SysMenu::where('id', $data['id'])
                ->update($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "更新 菜单");
        return $this->ajax_return(200, '恭喜你，操作成功！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $result = SysMenu::destroy($id);
        $this->log(__CLASS__, __FUNCTION__, $request, "删除 菜单");
        return $this->ajax_return(200, '操作成功！');
    }

    /**
     * orderUpdate
     * @return array $resulr
     */
    public function orderUpdate(Request $request){
        $order = $request->_order;
        $order = json_decode($order,true);
        if($this->sortUpdate($order)){
            return $this->ajax_return(200, '操作成功！');
        }
    }

    /**
     * sortUpdate
     * @return bool $resulr
     */
    private function sortUpdate($order, $pid = 0){
        foreach($order as $k => $sort){
            $id = $sort['id'];
            $dbsort = SysMenu::find($id);
            if($k != $dbsort->order || $pid != $dbsort->pid){
                SysMenu::where('id', $id)->update(['order' => $k, 'parent_id' => $pid]);
            }
            if(isset($sort['children'])){
                $this->sortUpdate($sort['children'], $id);
            }
        }
        return true;
    }

    /**
     * find all menus
     * @return array menuList
     */
    public function AllMenus(){
        $menuList = SysMenu::where('is_use', 1)
                    ->orderBy('order', 'asc')
                    ->get()
                    ->toArray();

        $data = [];

        $newMenuList = [];
        foreach($menuList as $key => $menus){
            $newMenuList[$menus['id']] = $menus;
        }
        unset($menuList);

        foreach($newMenuList as $k => $menu){
            if(isset($newMenuList[$menu['parent_id']])){
                $newMenuList[$menu['parent_id']]['child'][] = &$newMenuList[$menu['id']];
            }else{
                $data[] = &$newMenuList[$menu['id']];
            }
        }
        return $data;
    }

    /*
    * 获取菜单数据
    */
    public function getMenuList()
    {
        // $user = auth()->guard('admin')->user()->syncRoles('superAdministrator');
        //判断缓存是否存在, 是否调试模式
        if (config('app.debug') || !session()->has(config('admin.cache.menuList'))) {
            $menu = self::AllMenus();
            session([config('admin.cache.menuList') => $menu]);
        }else{
            $menu = session(config('admin.cache.menuList'));
        }
        return $this->initMenuList($menu);
    }

    //菜单列表视图
    public function initMenuList($menus){
        
        if ($menus){
            $item = '';
            foreach ($menus as $v){
                //权限判断
                $user = auth()->guard('admin')->user();
                if(!$user->hasRole('superAdministrator')){
                    if(!$user->hasPermissionTo($v['unique_name'])){
                        continue;
                    }
                }
                $item .= $this->getNetableItem($v);
            }
            return $item;
        }
        return '暂无菜单';
    }

    //返回菜单 HTML代码
    public function  getNetableItem($data){
        if (isset($data['child'])){
            return $this->getHandleList($data);
        }
        return '<li class="nav-item"><a href="'.$data['uri'].'" class="nav-link"><i class="nav-icon fa '.$data['icon'].'"></i><p>'.$data['title'].'</p></a></li>';
    }

    //判断是否有子集
    public function getHandleList($data){
        $handle = '<li class="nav-item has-treeview"><a href="#" class="nav-link"><i class="nav-icon fa '.$data['icon'].'"></i><p>'.$data['title'].'<i class="fa fa-angle-left right"></i></p></a><ul class="nav nav-treeview">';

        foreach ($data['child'] as $v){
            //权限判断
            $user = auth()->guard('admin')->user();
            if(!$user->hasRole('superAdministrator')){
                if(!$user->hasPermissionTo($v['unique_name'])){
                    continue;
                }
            }
            $handle .= $this->getNetableItem($v);
        }
        $handle .= '</ul></li>';

        return $handle;
    }

    //icon list
    public function sys_icon(){
        return view('admin.base.menu.iconList');
    }

}
