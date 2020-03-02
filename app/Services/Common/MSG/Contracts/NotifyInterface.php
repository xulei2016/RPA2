<?php


namespace App\Services\Common\MSG\Contracts;

use App\Services\Common\MSG\SMS;

interface NotifyInterface
{

    /**
     * @param $notify_to
     * @param SMS $result
     * @return mixed
     */
    public function notify($notify_to, $result);

}