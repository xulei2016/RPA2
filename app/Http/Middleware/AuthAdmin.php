<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\models\admin\Base\SysLog;
use App\models\admin\admin\SysAdmin;
use Illuminate\Support\Facades\Auth;

class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if(!Auth::guard($guard)->check()) {

            return redirect('admin/login');
        }

        $uid = Auth::guard('admin')->user()->id;
        $path = $request->path();
        $input = $request->all();
        $method = $request->method();
        $ip = $request -> ip();
        self::writeLog($uid, $input, $path, $method, $ip);

        return $next($request);
    }

    /**
     * write log to db
     * @param user account user
     * @param params request param
     * @param path 
     * @param method 
     * @param ip
     */
    public function writeLog($uid, $input, $path, $method, $ip){
        $log = new SysLog;
        $log->setAttribute('user_id', $uid);
        $log->setAttribute('path', '/'.$path);
        $log->setAttribute('method', $method);
        $log->setAttribute('ip', $ip);
        $log->setAttribute('data', json_encode($input, JSON_UNESCAPED_UNICODE));
        $log->save();
    }
}
