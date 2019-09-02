<?php

namespace App\Http\Controllers\admin\base;

use App\Models\Admin\Base\SysConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Base\BaseAdminController;

/**
 * SysController
 * @author lay
 * @since 2018-10-25
 */
class SysController extends BaseAdminController
{
    /**
     * dashboard
     */
    public function index(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 首页");
        return view('admin.index.index');
    }
    
    /**
     * 主页
     */
    public function get_index(Request $request){
        $admin = session('sys_admin');
        $info = $this->sys_info();
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 首页");
        return view('admin/index/dashboard', ['info'=>$info,'admin'=>$admin]);
    }

    /**
     * 清除缓存
     */
    public function clean_cache(){
        if($this->del_cache('sys_info') && $this->del_cache('sys_admin')){
            $this->authCacheInfo(false);
            return $this->ajax_return(200, '缓存清除成功！');
        }else{
            return $this->ajax_return(500, '缓存清除失败！请联系管理员处理');
        };
    }

    /**
     * 控制面板
     */
    public function dashboard(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 控制面板");
        return view('admin/admin/admin/index');
    }

    /**
     * 未知路由
     */
    public function notAllow(){
        return view('errors/noAllow');
    }

    /**
    * 系统
    */
    private function sys_info(){
        $info['sys'] = [
            '设备信息'      => php_uname() ,
            '协议'          => $_SERVER['SERVER_PROTOCOL'] ,
            'PHP 版本'      => 'PHP/'.PHP_VERSION ,
            'Laravel 版本'  => app()->version() ,
            'CGI'           => php_sapi_name() ,
            '服务'          => array_get($_SERVER, 'SERVER_SOFTWARE') ,
            '服务允许上传最大文件'  => get_cfg_var ("upload_max_filesize")?get_cfg_var ("upload_max_filesize"):"不允许上传附件" ,

            '缓存驱动'      => config('cache.default') ,
            'Session驱动'   => config('session.driver') ,
            '队列驱动'      => config('queue.default') ,

            '时区'          => config('app.timezone') ,
            'Locale'        => config('app.locale') ,
            'Env'           => config('app.env') ,
            'URL'           => config('app.url') ,
        ];
        $con = mysqli_connect(env('DB_HOST'),env('DB_USERNAME'),env('DB_PASSWORD'),env('DB_DATABASE'));
        $info['database'] = [
            '数据库版本'    => mysqli_get_server_info($con),
        ];
        // exec("wmic LOGICALDISK get name,Description,filesystem,size,freespace",$info['disk']);

        return $info;
    }

    /**
     * 清除缓存
     */
    public function clearCache(){
        if($this->del_cache('menuList') && $this->del_cache('sys_admin')){
            $this->authCacheInfo(false);
            return $this->ajax_return(200, '缓存清除成功！');
        }else{
            return $this->ajax_return(500, '缓存清除失败！请联系管理员处理');
        };
    }

    /**
     * 系统设置
     */
    public function setting(){
        //获取配置分组
        $item_group = SysConfig::groupBy("item_group")->pluck("item_group");
        $sysconfig = SysConfig::get();
        return view('admin.Base.system.index',['item_group' => $item_group,'sysconfig' => $sysconfig]);
    }

    public function update_config(Request $request)
    {
        $data = $request->all();
        foreach($data as $k => $v){
            SysConfig::where("item_key","=",$k)->update(["item_value" => $v]);
        }
        return $this->ajax_return(200, '配置更新成功！');
    }
    
    /**
     * 400
     */
    public function error400(Request $request){
        return view('errors.400');
    }
    
    /**
     * 401
     */
    public function error401(Request $request){
        return view('errors.401');
    }
    
    /**
     * 402
     */
    public function error402(Request $request){
        return view('errors.402');
    }
    
    /**
     * 403
     */
    public function error403(Request $request){
        return view('errors.403');
    }
    
    /**
     * 404
     */
    public function error404(Request $request){
        return view('errors.404');
    }
    
    /**
     * 500
     */
    public function error500(Request $request){
        return view('errors.500');
    }


}
