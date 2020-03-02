<?php


namespace App\Services\Common\MSG\Contracts;


use App\Services\Common\MSG\Support\Config;

interface LogInterface
{
    /**
     * @param PhoneNumberInterface $to
     * @param MessageInterface $message
     * @return mixed
     */
    public function beforeSend(PhoneNumberInterface $to, MessageInterface $message);

    /**
     * @param $id
     * @param $result
     * @return mixed
     */
    public function afterSend($id, $result);
}