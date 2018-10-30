<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\models\admin\Base\SysLog;
use App\models\admin\admin\SysAdmin;
use Illuminate\Support\Facades\Auth;

class OperationLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $req, Closure $next) 
    {
        // $uid = Auth::guard('admin')->user()->id;
        // $path = $request->path();
        // $input = $request->all();
        // $method = $request->method();
        // $ip = $request -> ip();
        // self::writeLog($uid, $request, $path, $method, $ip);
        // if(Auth::guard('admin')->check()) {
        //     $user_id = (int) Auth::guard('admin')->user()->id;
        // }
        // $input = $req->all();
        // $log = new SysLog;
        // $log->setAttribute('user_id', $user_id);
        // $log->setAttribute('path', $req->path());
        // $log->setAttribute('method', $req->method());
        // $log->setAttribute('ip', $req->ip());
        // $log->setAttribute('input', json_encode($input, JSON_UNESCAPED_UNICODE));
        // $log->save();
        return $next($req);
    }

    /**
     * write log to db
     * @param user account user
     * @param params request param
     * @param path 
     * @param method 
     * @param ip
     */
    public function writeLog($uid, $request, $path, $method, $ip){
        $user = SysAdmin::where('uid',$uid)->first();

        if($user) {
            $user_id = $user->userid;
        }

        $log = new SysLog;
        $log->setAttribute('user_id', $user_id);
        $log->setAttribute('path', $path);
        $log->setAttribute('method', $method);
        $log->setAttribute('ip', $ip);
        $log->setAttribute('input', json_encode($input, JSON_UNESCAPED_UNICODE));
        $log->save();
    }
}
