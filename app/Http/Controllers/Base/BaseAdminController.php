<?php

namespace App\Http\Controllers\base;

use Illuminate\Http\Request;
use App\Models\Admin\Base\SysMenu;
use App\Models\Admin\Admin\SysAdmin;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Base\SysConfig;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Base\BaseController;

/**
 * 基础控制器
 * @author hsu lay
 * @since 2018/2
 */
class BaseAdminController extends BaseController
{
    //session变量
    protected $sys_info;

    //construct
    public function __CONSTRUCT(){
        // $this->base_memcache();
    }

    //------------------系统session管理-----------------

    //系统session判断
    public function base_memcache(){
        if (!session()->has('sys_info')) {
            //存入session
            $sys_info = $this->get_menu();
            session(['sys_info' => $sys_info]);
        }
        // $this->sys_info = session('sys_info');
    }

    //获取菜单
    public function get_menu(){
        $menus = SysMenu::where([['is_use','=', 1],['parent_id','=',0]])
                        ->orderBy('order', 'asc')
                        ->get()
                        ->toArray();
        $config = sysConfig::get()
                        ->toArray();

        foreach($menus as &$menu){
            $childs = SysMenu::where([['is_use','=', 1],['parent_id','=',$menu['id']]])
                    ->orderBy('order', 'asc')
                    ->get();
            if($childs){
                $menu['child'][] = $childs;
            }
        }

        $new = [];
        //格式化处理
        foreach($config as $k){
            $new[$k['item_key']] = $k['item_value'];
        }

        return [
            'top_menu' => $top_menu,
            'config' => $new,
            'menus' => $menus
        ];
    }

    /**
     * 地址分析
     */
    public function analysis_url(){
        //获取网页地址
        $url = $_SERVER['REQUEST_URI'];
        $url = strpos($url,'?') ? substr($url,0,strpos($url,'?')) : $url ;
        foreach (session('sys_info')['menus'] as $menu){
            if(strpos($url, $menu['unique_name'])){
                Cache::put('active_list', [
                    'active_id' => $menu['team_id'], 
                    'active_name' => $menu['name'],
                    'active_unique_name' => $menu['unique_name'],
                    'active_icon' => $menu['icon']
                ], '30');
                break;
            }
        }
    }

    /**
     * 删除session
     * flush 清除session并不管什么session键前缀，而是从session系统中移除所有数据，所以在使用这个方法时如果其他应用与本应用有共享session时需要格外注意。
     */
    public function del_cache($name = null){
        if($name){
            session()->forget($name);
        }else{
            session()->flush();
        }
        return true;
    }

    //获取数组数据
    public function get_one($data){
        foreach($data as $param){
            yield $param;
        }
    }
    
    //------------------日志管理-----------------

     /**
     * log
     * @param string $controller 控制器
     * @param string $action 动作
     * @param $request request
	 * @param string $desc 描述
     */
    public function log($controller, $action, $request, $desc) {
        dd(auth());
        $user_id = 0;
        if(auth()->guard('admin')->check()) {
            $user_id = (int) auth()->guard('admin')->user()->id;
            $account = auth()->guard('admin')->user()->name;
        }else{
            $account = $request->name;
        }
        $request->session()->has('sys_admin') ? session('sys_admin') : $this->authCacheInfo(false) ;
        $admin = session('sys_admin');

        $log = new \App\Models\Admin\Base\SysLog();
        $log->setAttribute('ip', $request->ip());
        // $log->setAttribute('controller', strrchr($controller, '\\'));
        $log->setAttribute('action', $action);
        $log->setAttribute('simple_desc', $desc);
        $log->setAttribute('user_id', $user_id);
        $log->setAttribute('account', $account);
        $log->setAttribute('path', $request->path());
        $log->setAttribute('method', $request->method());
        $log->setAttribute('data', json_encode($request->all(), JSON_UNESCAPED_UNICODE));
        $log->setAttribute('agent', $admin['lastAbbAgent']);
        $log->setAttribute('add_time', $this->getTime());
        $log->save();
    }
}
