<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';
    
    //自定义路由开始
    protected $Index = 'App\Http\Controllers\Index';
    protected $Admin = 'App\Http\Controllers\Admin';
    protected $ZT = 'App\Http\Controllers\Admin';
    protected $API = 'App\Http\Controllers\api';
    //自定义路由结束

    
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //路由过滤
        Route::pattern('id', '[0-9]+');

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        //遍历admin.php后缀的路由文件
        $mapAdminRoutes = $this->loadRoutesFile(base_path('routes/web/Admin'));
        $mapFrontRoutes = $this->loadRoutesFile(base_path('routes/web/Front'));


        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //自定义map
        $this->mapRoutes($mapAdminRoutes, 'admin.php', 'admin', 'web', $this->Admin);
        $this->mapRoutes($mapFrontRoutes, 'front.php', '', 'web', $this->Index);
        $this->mapIndexRoutes();

    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->API)
             ->group(base_path('routes/web/api.php'));
    }
    
    /**
     * Define the "index" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "index" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapIndexRoutes()
    {
        Route::middleware('web')
             ->namespace($this->Index)
             ->group(base_path('routes/web/Front/front.php'));
    }
    
    /**
     * Define routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapRoutes(array $mapRoutePath, string $endFileName, string $prefix, string $middleware, string $namespace)
    {
        foreach ($mapRoutePath as $routes){
            if(ends_with($routes, $endFileName)){//匹配需要分配web中间的文件
                Route::prefix($prefix)
                    ->middleware($middleware)
                    ->namespace($namespace)
                    ->group($routes);
            }
        }
    }

    /**
     * 递归加载文件
     *
     * @param [string] $path 文件路径
     * @Description
     */
    protected function loadRoutesFile($path){
        $allRoutesFilePath = array();
        foreach(glob($path) as $file){
            if(is_dir($file)){
                $allRoutesFilePath = array_merge($allRoutesFilePath, $this->loadRoutesFile($file.'/*'));
            }else{
                $allRoutesFilePath[] = $file;
            }
        }
        return $allRoutesFilePath;
    }
}
