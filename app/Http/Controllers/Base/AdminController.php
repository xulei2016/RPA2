<?php

namespace App\Http\Controllers\base;

use Illuminate\Http\Request;
use App\Models\Admin\Admin\SysAdmin;
use App\Models\Admin\Base\SysMenu;
use App\Models\Admin\Base\SysConfig;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Base\BaseController;

/**
 * 基础控制器
 * @author hsu lay
 * @since 2018/2
 */
class AdminController extends BaseController
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
            $this->authCacheInfo(false);
            $sys_info = $this->get_menu();
            session(['sys_info' => $sys_info]);
        }
        // $this->sys_info = session('sys_info');
        // $this->analysis_url();
    }

    //获取菜单
    public function get_menu(){
        $id = session('sys_admin')['id'];//auth()->guard('admin')->user()->id;
        $user = SysAdmin::find($id);
        $top_menu = sysMenuTop::where('status', 1)
                        ->orderBy('sort', 'asc')
                        ->get()
                        ->toArray();
        $menus = sysMenu::where('status', 1)
                        ->orderBy('sort', 'asc')
                        ->get()
                        ->toArray();
        $config = sysConfig::get()
                        ->toArray();

        if(!$user->hasRole('super_admin')){
            foreach($top_menu as $k => &$top){
                if(!$user->can($top['alias'])){
                    unset($top_menu[$k]);
                    continue;
                }
                $top['menus'] = [];
                foreach($menus as $menu){
                    if($menu['team_id'] == $top['id']){
                        if($user->can($menu['unique_name'])){
                            array_push($top['menus'], $menu);
                        }
                    }
                }
                if (empty($top['menus'])){unset($top_menu[$k]);};
            }
        }else{
            foreach($top_menu as $k => &$top){
                $top['menus'] = [];
                foreach($menus as $menu){
                    if($menu['team_id'] == $top['id']){
                        array_push($top['menus'], $menu);
                    }
                }
                if (empty($top['menus'])){unset($top_menu[$k]);};
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
    
    /**
     * session管理员信息
     * @return bool 
     */
    function authCacheInfo ($type = TRUE){
        //更新登录信息
        $admin_info = auth()->guard('admin')->user();
        if(!$admin_info){
            header('Location: /admin/logout');exit;
        }
        $id = (int) $admin_info->id;
        $admin = new \App\Models\Admin\Admin\SysAdmin();
        $info['lastIp'] = $this->getRealIp();
        $info['lastTime'] = $this->getTime();
        $info['isMobile'] = $this->isMobile()['isMobile'] ? 1 : 0 ;
        $info['lastAgent'] = $_SERVER['HTTP_USER_AGENT'];
        $info['lastAbbAgent'] = $this->isMobile()['userAgent'];
        $info['lastAddress'] = json_encode($this->getAreaByIp($info['lastIp']));
        if($type)
            $admin::where('id', $id)->update($info);
        
        //头像、权限信息
        $headImg = $admin->find($id)->headImg;

        //快捷获取管理员信息可从此处添加 $admin_info->***
        $info['id'] = $id;
        $info['role'] = $admin_info->role;
        $info['headImg'] = $headImg->thumb;
        $info['name'] = $admin_info->name;
        $info['realName'] = $admin_info->realName;
        $info['email'] = $admin_info->email;
        $info['theme'] = $admin_info->theme ? $admin_info->theme : 'lightseagreen' ;
        $info['lastTime'] = $admin_info->lastTime;
        $info['lastIp'] = $admin_info->lastIp;
        $info['isMobile'] = $admin_info->isMobile;
        $info['lastAgent'] = $admin_info->lastAgent;
        $info['lastAddress'] = json_decode($info['lastAddress'], true);
        session(['sys_admin' => $info]);
        return true;
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
        $user_id = 0;
        if(auth()->guard('admin')->check()) {
            $user_id = (int) auth()->guard('admin')->user()->id;
            $account = auth()->guard('admin')->user()->name;
        }else{
            $account = $request->name;
        }
        $request->session()->has('sys_admin') ? session('sys_admin') : $this->authCacheInfo(false) ;
        $admin = session('sys_admin');

        $admin['lastAbbAgent'] = $admin['lastAbbAgent'] ? $admin['lastAbbAgent'] : $this->isMobile()['userAgent'] ;
        $admin['lastAddress'] = isset($admin['lastAddress']) ? $admin['lastAddress'] : $this->getAreaByIp($request->ip());
        
        $log = new \App\Models\Admin\Base\SysLog();
        $log->setAttribute('ip', $request->ip());
        $log->setAttribute('controller', strrchr($controller, '\\'));
        $log->setAttribute('action', $action);
        $log->setAttribute('simple_desc', $desc);
        $log->setAttribute('user_id', $user_id);
        $log->setAttribute('account', $account);
        $log->setAttribute('path', $request->path());
        $log->setAttribute('method', $request->method());
        $log->setAttribute('data', json_encode($request->all(), JSON_UNESCAPED_UNICODE));
        $log->setAttribute('province', $admin['lastAddress']['country']);
        $log->setAttribute('city', $admin['lastAddress']['city']);
        $log->setAttribute('agent', $admin['lastAbbAgent']);
        $log->setAttribute('add_time', $this->getTime());
        $log->save();
    }
}
