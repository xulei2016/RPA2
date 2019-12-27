<?php

namespace App\Http\Controllers\admin\base;

use DB;
use App\Models\Admin\Base\SysVersionUpdate;
use App\Models\Admin\Base\SysConfig;
use App\Models\Admin\Admin\SysAdmin;
use App\Models\Admin\Api\RpaApiLog;
use App\Models\Admin\Base\SysLog;
use App\Models\Admin\RPA\rpa_taskcollections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
        if(!auth()->Guard('admin')->check())
        {
            return redirect('/');
        }

        //用户数
        $data['countUser'] = SysAdmin::where('type', 1)->count();
        //日活
        $date = date('Y-m-d', strtotime('-1 day'));
        $data['countYUser'] = SysAdmin::where([['type', 1],['updated_at','>=', "{$date}"]])->count();
        //未读邮件
        $data['notification_count'] = auth()->guard('admin')->user()->notification_count;
        //执行任务数
        $data['countTask'] = rpa_taskcollections::count();
        //条用接口数
        $data['countApi'] = RpaApiLog::count();
        //活动内容
        $user_id = auth()->guard('admin')->user()->id;
        $data['footprint'] = DB::select("select count(*)c,simple_desc from sys_logs where user_id = {$user_id} GROUP BY controller,simple_desc ORDER BY c desc limit 10");
        $data['pie_labels'] = '';
        $data['pie_datas'] = '';
        $data['pie_all'] = 0;
        foreach($data['footprint']  as $footprint){
            $data['pie_all'] += $footprint->c;
        }
        
        foreach($data['footprint']  as $footprint){
            $data['pie_labels'] .= "'{$footprint->simple_desc}',";
            $data['pie_datas'] .= "{$footprint->c},";
        }

        $data['pie_labels'] = trim($data['pie_labels'],',');
        $data['pie_datas'] = trim($data['pie_datas'],',');
        
        $data = array_merge($data, self::sys_info());
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 首页");

        $versionUpdateList = SysVersionUpdate::where('status', 1)->orderBy('id', 'desc')->limit(3)->get();

        return view('admin.index.index', ['data'=>$data, 'versionUpdateList' => $versionUpdateList]);
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
		if(!Cache::has('sys_info')){
			$info['SYS'] = [
				'PHP_OS'      => php_uname() ,
				'SERVER_PROTOCOL'          => $_SERVER['SERVER_PROTOCOL'] ,
				'PHP_VERSION'      => 'PHP/'.PHP_VERSION ,
				'Laravel_VERSION'  => app()->version().'LARAVEL' ,
				'CGI'           => php_sapi_name() ,
				'SERVER_INFO'          => array_get($_SERVER, 'SERVER_SOFTWARE') ,
				'FILE_UPLOAD_MAX_SIZE'  => get_cfg_var ("upload_max_filesize")?get_cfg_var ("upload_max_filesize"):"不允许上传附件" ,

				'CACHE'      => config('cache.default') ,
				'Session'   => config('session.driver') ,
				'QUEUE'      => config('queue.default') ,

				'TIMEZONE'          => config('app.timezone') ,
				'Locale'        => config('app.locale') ,
				'Env'           => config('app.env') ,
				'URL'           => config('app.url') ,
			];
			$con = mysqli_connect(env('DB_HOST'),env('DB_USERNAME'),env('DB_PASSWORD'),env('DB_DATABASE'),env('DB_PORT'));
			$info['DATABASE'] = [
				'ALLOW_PERSISTENT' => @get_cfg_var("mysql.allow_persistent")?"是 ":"否",
				'MAX_LINKS' => @get_cfg_var("mysql.max_links")==-1 ? "不限" : @get_cfg_var("mysql.max_links"),
				'MYSQL_VERSION'    => mysqli_get_server_info($con),
			];
			$info['CACHE'] = [
				'cache_time' => date('Y-m-d H:i:s')
            ];
            // $info['DISK'] = $this->get_spec_disk('all');
            // $info['DISK_JSON'] = json_encode($info['DISK']);

            Cache::forget("sys_info");
            Cache::add("sys_info", $info, 3600);
		}

        return Cache::get('sys_info');
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
        Cache::forget('sysConfigs');
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
