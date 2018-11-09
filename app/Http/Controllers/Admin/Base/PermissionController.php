<?php

namespace App\Http\Controllers\Admin\Base;

use App\Models\Admin\Base\SysPermission;
use Illuminate\Http\Request;
use App\Http\Controllers\Base\BaseAdminController;

/**
 * 权限树模型管理
 * @author hsu lay
 * @since 2018/2
 */
class PermissionController extends BaseAdminController
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
     * @param  \App\permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $top_list = SysPermission::where('table','=',1)->orderBy('sort','asc')->get();
        $lists = $this->get_group_menu($top_list);
        return view('admin.base.permission.index', ['lists' => $lists]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.base.permission.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->get_params($request, ['pid','name','guard_name','status','sort','desc'], false);
        $result = SysPermission::create($data);
        // $this->log(__CLASS__, __FUNCTION__, $request, "添加菜单");
        return $this->ajax_return(200, '操作成功！');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\permission  $permission
     * @param  \DummyFullModelClass  $DummyModelVariable
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\permission  $permission
     * @param  \DummyFullModelClass  $DummyModelVariable
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\permission  $permission
     * @param  \DummyFullModelClass  $DummyModelVariable
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\permission  $permission
     * @param  \DummyFullModelClass  $DummyModelVariable
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = SysPermission::destroy($id);
        return $this->ajax_return(200, '操作成功！');
    }
    
    /**
     * sortUpdate
     * @return bool $resulr
     */
    protected function sortUpdate($order){
        foreach($order as $k => $sort){
            $id = $sort['id'];
            $dbsort = SysPermission::find($id);
            if($k != $dbsort->order){
                SysPermission::where('id', $id)->update(['order' => $k]);
            }
            if(isset($sort['children'])){
                $this->sortUpdate($sort['children']);
            }
        }
        return true;
    }

    /**
     * getTree
     */
    public function getTree(){
        $top_list = SysPermission::where('table','=',1)->orderBy('sort','asc')->get();
        $lists = $this->get_group_menu($top_list);
        return $this->ajax_return('200', '查询成功！', $lists);
    }

    /**
     * 查询分组
     */
    private function get_group_menu($menus){
        foreach($menus as $menu){
            $result = SysPermission::where('pid','=',$menu['id'])->orderBy('sort','asc')->get();
            $result = $this->get_group_menu($result);
            if(!$result->isEmpty()){
                // array_splice($menus, 1, 0, $result);
                $menu['child'] = $result;
            }
        }
        return $menus;
    }
}
