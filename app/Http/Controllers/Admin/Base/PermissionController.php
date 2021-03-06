<?php

namespace App\Http\Controllers\Admin\Base;

use Spatie\Permission\Models\Permission;
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
     * Display a listing of the resource.
     *
     * @param  \App\permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lists = $this->getAllPermissions();
        $lists = $this->initTree($lists);
        $this->log(__CLASS__, __FUNCTION__, $request, "权限 列表页");
        return view('admin.base.permission.index', ['lists' => $lists]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
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
        $data = $this->get_params($request, [['pid',0],'name','guard_name','status','sort','desc','table']);
        $data['table'] = isset($data['table']) ? $data['table']+1 : 1 ;
        $result = Permission::create($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "添加 权限");
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
        $info = Permission::where('id', $id)->first();
        $this->log(__CLASS__, __FUNCTION__, $request, "修改权限菜单页");
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
        $data = $this->get_params($request, ['id',['pid',0],'name','guard_name','status','sort','desc','table'], false);
        $data['table'] = isset($data['table']) ? $data['table']+1 : 1 ;
        $result = Permission::where('id', $data['id'])->update($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "更新 权限");
        return $this->ajax_return(200, '操作成功！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\permission  $permission
     * @param  \DummyFullModelClass  $DummyModelVariable
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $result = Permission::destroy($id);
        $this->log(__CLASS__, __FUNCTION__, $request, "删除 权限");
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
            $dbsort = Permission::find($id);
            if($k != $dbsort->sort || $table != $dbsort->table || $pid != $dbsort->pid){
                Permission::where('id', $id)->update(['sort' => $k, 'table' => $table, 'pid' => $pid]);
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
        $lists = $this->getAllPermissions();
        return $this->ajax_return('200', '查询成功！', $lists);
    }

    /**
     * initTree function
     *
     * @Description 权限树生成
     * @param [type] $data
     * @return string $html
     */
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

        
    /**
     * permissions
     * 
     * @return \Illuminate\Http\Response
     */
    private function getAllPermissions()
    {
        $menuList = Permission::where('status', 1)
                    ->orderBy('sort', 'asc')
                    ->get()
                    ->toArray();

        $data = [];

        $newMenuList = [];
        foreach($menuList as $key => $menus){
            $newMenuList[$menus['id']] = $menus;
        }
        unset($menuList);

        foreach($newMenuList as $k => $menu){
            if(isset($newMenuList[$menu['pid']])){
                $newMenuList[$menu['pid']]['child'][] = &$newMenuList[$menu['id']];
            }else{
                $data[] = &$newMenuList[$menu['id']];
            }
        }
        return $data;
    }
}
