<?php


namespace App\Services\Common\MSG\Facades;

use Illuminate\Support\Facades\Facade;

class SMSMsg extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'SMSMsg';
    }
}