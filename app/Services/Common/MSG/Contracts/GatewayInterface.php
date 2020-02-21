<?php


namespace App\Services\Common\MSG\Contracts;

use App\Services\Common\MSG\Support\Config;

/**
 * Interface GatewayInterface
 * @package App\Services\Common\MSG\Contracts
 */
interface GatewayInterface
{
    /**
     * Get gateway name.
     *
     * @return string
     */
    public function getName();

    /**
     * @param PhoneNumberInterface $to
     * @param MessageInterface $message
     * @param Config $config
     * @return mixed
     */
    public function send(PhoneNumberInterface $to, MessageInterface $message, Config $config);
}