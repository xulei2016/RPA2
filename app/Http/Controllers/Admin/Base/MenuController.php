<?php

namespace App\Http\Controllers\Admin\Base;

use Illuminate\Http\Request;
use App\Models\Admin\Base\SysMenu;
use App\Http\Controllers\Base\BaseAdminController;

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
    private function AllMenus(){
        $menuList = SysMenu::where('is_use', 1)
                    ->orderBy('order', 'asc')
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
        return $menuList;
    }

    /*
    * 获取菜单数据
    */
    public function getMenuList()
    {
        //判断缓存是否存在, 是否调试模式
        if (!config('app.debug') || !session()->has(config('admin.cache.menuList'))) {
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
        return '<li><a href="'.$data['uri'].'"><i class="fa '.$data['icon'].'"></i><span>'.$data['title'].'</span></a></li>';
    }

    //判断是否有子集
    public function getHandleList($data){
        $handle = '<li class="treeview"><a href="'.$data['uri'].'" onclick="return false"><i class="fa '.$data['icon'].'"></i><span>'.$data['title'].'</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">';

        foreach ($data['child'] as $v){
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
