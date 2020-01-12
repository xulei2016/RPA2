<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DAServiceProvider extends ServiceProvider
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
        $this->app->bind('App\Services\DA\AreaDA', function ($app) {
            return new LoginDA();
        });
    }
}
