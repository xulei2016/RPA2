<?php

namespace App\Http\Middleware;

use App\Models\Admin\Admin\SysAdminAlert;
use App\Models\Admin\Base\SysMessage;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthAdmin
{

    /**
     * @var obj
     */
    protected $request;

    /**
     * @var obj
     */
    protected $guard;


    /**
     * Admin Middleware
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        $this->guard = Auth::guard($guard);
        if (!$this->guard->check() || !self::singleLogin()) {

            return redirect()->guest('admin/login');
        }

        //缓存当前路由
        $pathInfo = explode('/', $this->request->path());
        $permission = end($pathInfo);
        if (session('menuList') && $route = self::cacheKeepRoute(session('menuList'), $permission)) {
            session(['keepMenu' => $route]);
        } else {
            $this->request->session()->forget('keepMenu');
        }

        return $next($request);
    }

    /**
     * cacheKeepRoute
     */
    public function cacheKeepRoute($routeList, $permission)
    {
        $route = '';

        foreach ($routeList as $list) {
            if (trim(strrchr($list['uri'], '/'), '/') == $permission) {
                return $route = $list;
            }
            if (isset($list['child'])) {
                if ($route = self::cacheKeepRoute($list['child'], $permission)) {
                    return $route;
                }
            }
        }
        return $route;
    }

    /**
     * singleLogin function 单用户登录
     *
     * @return void
     */
    private function singleLogin()
    {
        if ($this->guard->user()->login_protected) {
            $token = $this->request->session()->get('_token');
            if ($token != $this->guard->user()->last_session) {

                if (auth()->Guard('admin')->check()) {

                    //异常通知
                    SysMessage::create([
                        'title' => '系统通知 - 账号异常行为通知',
                        'content' => '您的账号已在其他设备登录，若不是本人操作，请立即修改密码！！！',
                        'user' => $this->guard->user()->id,
                        'mode' => 1,
                        'type' => 1
                    ]);

                    SysAdminAlert::create([
                        'user_id' => $this->guard->user()->id,
                        'title' => '账号异常提醒',
                        'content' => '',
                    ]);

                    //退出登录
                    auth()->Guard('admin')->logout();
                }

                //清除缓存
                $this->request->session()->flush();

                return false;
            }
        }

        return true;
    }
}
