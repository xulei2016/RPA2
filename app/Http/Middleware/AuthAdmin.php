<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        
        //缓存当前路由
        $pathInfo = explode('/', $request->path());
        $permission = end($pathInfo);
        if(session('menuList') && $route = self::cacheKeepRoute(session('menuList'), $permission)){
            session(['keepMenu' => $route]);
            
            //缓存菜单列表
            self::cacheTagsList($route);
        }else{
            $request->session()->forget('keepMenu');
        }

        return $next($request);
    }

    /**
     * cacheKeepRoute
     */
    public function cacheKeepRoute($routeList, $permission)
    {
        $route = '';
        
        foreach($routeList as $list){
            if(trim(strrchr($list['uri'], '/'),'/') == $permission){
                return $route = $list;
            }
            if(isset($list['child'])){
                if($route = self::cacheKeepRoute($list['child'], $permission)){
                    return $route;
                }
            }
        }
        return $route;
    }

    /**
     * cacheTagsList
     */
    public function cacheTagsList($route)
    {
        $tagsList = session('tagsList');
        if(!$tagsList){
            $tagsList = [
                1 => [
                    'id' => 1,
                    'uri' => '/admin',
                    'title' => '首页'
                ]
            ];
        }

        if(!isset($tagsList[$route['id']])){

            $tagsList[$route['id']] = $route;

        }
        session(['tagsList' => $tagsList]);
        return $tagsList;
    }
}
