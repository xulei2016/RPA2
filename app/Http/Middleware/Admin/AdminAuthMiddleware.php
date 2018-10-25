<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // public function handle($request, Closure $next)
    // {

    //     if (Auth::guard('admin')->guest()) {
    //         if ($request->ajax() || $request->wantsJson()) {
    //             return response('Unauthorized', 401);
    //         } else {
    //             return redirect()->guest('admin/login');
    //         }
    //     }
    //     return $next($request);
    // }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->guard('admin')->check()) {
            return redirect('/admin');
        }
        return $next($request);
    }

    public function handle1($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            // 根据不同 guard 跳转到不同的页面
            $url = $guard ? 'admin/dash':'/admin';
            return redirect($url);
        }

        return $next($request);
    }
}
