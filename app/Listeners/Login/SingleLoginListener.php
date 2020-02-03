<?php

namespace App\Listeners\Login;

use App\Events\Login\SingleLoginEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SingleLoginListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SingleLoginEvent  $event
     * @return void
     */
    public function handle(SingleLoginEvent $event)
    {
        //是否内网地址登录
        
    }
}
