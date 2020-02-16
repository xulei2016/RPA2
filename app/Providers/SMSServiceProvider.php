<?php

namespace App\Providers;

use App\Services\Common\MSG\SMSMsg;
use Illuminate\Support\ServiceProvider;

class SMSServiceProvider extends ServiceProvider
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
        $this->app->bind('SMSMsg', function ($app) {
            return new SMSMsg();
        });
    }
}
