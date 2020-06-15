<?php

namespace App\Http\Controllers\admin\base;

use DB;
use App\Models\Admin\Base\SysVersionUpdate;
use App\Models\Admin\Base\SysConfig;
use App\Models\Admin\Admin\SysAdmin;
use App\Models\Admin\Api\ApiLog;
use App\Models\Admin\RPA\rpa_taskcollections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Base\BaseAdminController;

use App\Models\Admin\Admin\SysAdminAlert;
use App\Models\Admin\Base\SysMessage;

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
        if (!auth()->Guard('admin')->check()) {
            return redirect('/');
        }

        $last_week = date('Y-m-d', strtotime('-1week'));
        $last_month = date('Y-m-d', strtotime('-1month'));

        //用户提醒
        $data['alerts'] = auth()->guard('admin')->user()->alerts()->where([['state', '=', 0], ['created_at', '>', $last_week]])->get(['id', 'title', 'content', 'type', 'created_at'])->toArray();
        //未读邮件
        $data['notification_count'] = auth()->guard('admin')->user()->notification_count;

        //用户数
        $data['count']['countUser'] = SysAdmin::where('type', 1)->count();
        //日活
        $date = date('Y-m-d', strtotime('-1 day'));
        $data['count']['countYUser'] = SysAdmin::where([['type', 1], ['updated_at', '>=', "{$date}"]])->count();
        //执行任务数
        $data['count']['countTask'] = rpa_taskcollections::count();
        //调用接口数
        $data['count']['countApi'] = ApiLog::count();

        //近期操作
        $data['usulMenus'] = self::getUsulMenus($last_month);

        //我的活动内容
        $user_id = auth()->guard('admin')->user()->id;
        $data['myActivity'] = self::myActivity($user_id);

        //系统信息
//        $data['sysInfo'] = self::sys_info();
        $data = array_merge($data, self::sys_info());

        //版本更新历史
        $data['versionUpdateList'] = SysVersionUpdate::where('status', 1)->orderBy('id', 'desc')->limit(3)->get();


        $this->log(__CLASS__, __FUNCTION__, $request, "查看 首页");
        return view('admin.index.index', ['data' => $data]);
    }

    /**
     * 主页
     */
    public function get_index(Request $request)
    {
        $admin = session('sys_admin');
        $info = $this->sys_info();
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 首页");
        return view('admin/index/dashboard', ['info' => $info, 'admin' => $admin]);
    }

    /**
     * 清除缓存
     */
    public function clean_cache()
    {
        if ($this->del_cache('sys_info') && $this->del_cache('sys_admin')) {
            $this->authCacheInfo(false);
            return $this->ajax_return(200, '缓存清除成功！');
        } else {
            return $this->ajax_return(500, '缓存清除失败！请联系管理员处理');
        };
    }

    /**
     * 控制面板
     */
    public function dashboard(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 控制面板");
        return view('admin/admin/admin/index');
    }

    /**
     * 未知路由
     */
    public function notAllow()
    {
        return view('errors/noAllow');
    }

    /**
     * 系统
     */
    private function sys_info()
    {
        if (!Cache::has('sys_info')) {
            $info['SYS'] = [
                'PHP_OS' => php_uname(),
                'SERVER_PROTOCOL' => $_SERVER['SERVER_PROTOCOL'],
                'PHP_VERSION' => 'PHP/' . PHP_VERSION,
                'Laravel_VERSION' => app()->version() . 'LARAVEL',
                'CGI' => php_sapi_name(),
                'SERVER_INFO' => array_get($_SERVER, 'SERVER_SOFTWARE'),
                'FILE_UPLOAD_MAX_SIZE' => get_cfg_var("upload_max_filesize") ? get_cfg_var("upload_max_filesize") : "不允许上传附件",

                'CACHE' => config('cache.default'),
                'Session' => config('session.driver'),
                'QUEUE' => config('queue.default'),

                'TIMEZONE' => config('app.timezone'),
                'Locale' => config('app.locale'),
                'Env' => config('app.env'),
                'URL' => config('app.url'),
            ];
            $con = mysqli_connect(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'), env('DB_DATABASE'), env('DB_PORT'));
            $info['DATABASE'] = [
                'ALLOW_PERSISTENT' => @get_cfg_var("mysql.allow_persistent") ? "是 " : "否",
                'MAX_LINKS' => @get_cfg_var("mysql.max_links") == -1 ? "不限" : @get_cfg_var("mysql.max_links"),
                'MYSQL_VERSION' => mysqli_get_server_info($con),
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
     * 获取用户自定义首页展示内容 无自定义则使用默认
     * 时间优先、频次优先
     * @param $last_month
     * @return array
     */
    private function getUsulMenus($last_month)
    {
        $actList = [];
        $active = DB::select("select count(*),simple_desc,path,id from sys_logs where created_at >= {$last_month} and account = 'xuliang' and action = 'index' and simple_desc like '%查看%' group by simple_desc order by id desc, count(*) desc limit 5");
        foreach ($active as $item) {
            $m = explode(' ', $item->simple_desc);
            isset($m[1]) ? ($actList[$m[1]] = $item->path) : '';
        }
        return $actList;
    }

    /**
     * 我的活跃内容
     * @param $user_id
     * @return mixed
     */
    private function myActivity($user_id)
    {
        $data['footprint'] = DB::select("select count(*)c,simple_desc from sys_logs where user_id = {$user_id} GROUP BY controller,simple_desc ORDER BY c desc limit 10");
        $data['pie_labels'] = '';
        $data['pie_datas'] = '';
        $data['pie_all'] = 0;
        foreach ($data['footprint'] as $footprint) {
            $data['pie_all'] += $footprint->c;

            $data['pie_labels'] .= "'{$footprint->simple_desc}',";
            $data['pie_datas'] .= "{$footprint->c},";
        }

        $data['pie_labels'] = trim($data['pie_labels'], ',');
        $data['pie_datas'] = trim($data['pie_datas'], ',');
        return $data;
    }

    /**
     * 清除缓存
     */
    public function clearCache()
    {
        if ($this->del_cache('menuList') && $this->del_cache('sys_admin')) {
            $this->authCacheInfo(false);
            return $this->ajax_return(200, '缓存清除成功！');
        } else {
            return $this->ajax_return(500, '缓存清除失败！请联系管理员处理');
        }
    }

    /**
     * 系统设置
     */
    public function setting()
    {
        //获取配置分组
        $item_group = SysConfig::groupBy("item_group")->pluck("item_group");
        $sysconfig = SysConfig::get();
        return view('admin.Base.system.index', ['item_group' => $item_group, 'sysconfig' => $sysconfig]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function closeAlert(Request $request, $id)
    {
        return auth()->guard('admin')->user()->alerts()->where('id', $id)->update(['state' => 1]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function update_config(Request $request)
    {
        $data = $request->all();
        foreach ($data as $k => $v) {
            SysConfig::where("item_key", "=", $k)->update(["item_value" => $v]);
        }
        Cache::forget('sysConfigs');
        return $this->ajax_return(200, '配置更新成功！');
    }

    /**
     * @return array
     */
    public function keepAlive(Request $request)
    {
        $token = $request->session()->get('_token');
        if ($token === auth()->guard('admin')->user()->last_session) {
            return $this->ajax_return(200, 'success');
        }

        //异常通知
        SysMessage::create([
            'title' => '系统通知 - 账号异常行为通知',
            'content' => '您的账号已在其他设备登录，若不是本人操作，请立即修改密码！！！',
            'user' => auth()->guard('admin')->user()->id,
            'mode' => 1,
            'type' => 1
        ]);

        SysAdminAlert::create([
            'user_id' => auth()->guard('admin')->user()->id,
            'title' => '账号异常提醒',
            'content' => '您的账号近期存在登录异常行为，为保障您的账号安全，请及时修改密码。有问题请咨询金融科技部',
            'type' => 'danger'
        ]);

        return $this->ajax_return(500, 'fail');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function singleOut(Request $request)
    {
        return view('admin.singleOut');
    }

    /**
     * 400
     */
    public function error400(Request $request)
    {
        return view('errors.400');
    }

    /**
     * 401
     */
    public function error401(Request $request)
    {
        return view('errors.401');
    }

    /**
     * 402
     */
    public function error402(Request $request)
    {
        return view('errors.402');
    }

    /**
     * 403
     */
    public function error403(Request $request)
    {
        return view('errors.403');
    }

    /**
     * 404
     */
    public function error404(Request $request)
    {
        return view('errors.404');
    }

    /**
     * 500
     */
    public function error500(Request $request)
    {
        return view('errors.500');
    }

}
