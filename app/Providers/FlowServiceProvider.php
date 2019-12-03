<?php

namespace App\Providers;

use App\Services\Flow\Flow;
use Illuminate\Support\ServiceProvider;

class FlowServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Flow', function ($app) {
            return new Flow();
        });

        //使用bind绑定实例到接口以便依赖注入
        // $this->app->bind('App\Contracts\TestContract',function(){
        //     return new TestService();
        // });
    }
}
