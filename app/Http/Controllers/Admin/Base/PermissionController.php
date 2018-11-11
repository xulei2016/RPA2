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
        $lists = $this->initTree($lists);
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
        $data = $this->get_params($request, ['pid','name','guard_name','status','sort','desc','table'], false);
        $data['table'] = isset($data['table']) ? $data['table']+1 : 1 ;
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
    public function edit(Request $request, $id)
    {
        $info = SysPermission::where('id', $id)->first();
        return view('admin.base.permission.edit', ['info' => $info]);
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
        $data = $this->get_params($request, ['id','pid','name','guard_name','status','sort','desc','table'], false);
        $data['table'] = isset($data['table']) ? $data['table']+1 : 1 ;
        $result = SysPermission::where('id', $data['id'])->update($data);
        // $this->log(__CLASS__, __FUNCTION__, $request, "添加菜单");
        return $this->ajax_return(200, '操作成功！');
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
    protected function sortUpdate($order, $table = 0, $pid = 0){
        $table += 1;
        foreach($order as $k => $sort){
            $id = $sort['id'];
            $dbsort = SysPermission::find($id);
            if($k != $dbsort->sort || $table != $dbsort->table || $pid != $dbsort->pid){
                SysPermission::where('id', $id)->update(['sort' => $k, 'table' => $table, 'pid' => $pid]);
            }
            if(isset($sort['children'])){
                $this->sortUpdate($sort['children'], $table, $id);
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
            if(!$result->isEmpty()){
                $result = $this->get_group_menu($result);
                // array_splice($menus, 1, 0, $result);
                $menu['child'] = $result;
            }
        }
        return $menus;
    }

    public function initTree($data){
        $html = "<ol class='dd-list'>";
        foreach($data as $list){
            $html .= "<li class='dd-item' data-id=".$list['id'].">";
            $html .= "<div class='dd-handle'>".$list['desc']."</strong>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);' class='dd-nodrag'>".$list['name']."</a>";
            $html .= "<span class='pull-right dd-nodrag'>";
            $html .= "<a url='/admin/sys_permission/".$list['id']."/edit' onclick='operation($(this));'><i class='fa fa-edit'></i></a>";
            $html .= "<a href='javascript:void(0);' data-id=".$list['id']." class='tree_branch_delete'><i class='fa fa-trash'></i></a>";
            $html .= "</span></div>";
            if(isset($list['child'])){
                $html .= $this->initTree($list['child']);
            }
            $html .= "</li>";
        }
        $html .= "</ol>";
        return $html;
    }
}
