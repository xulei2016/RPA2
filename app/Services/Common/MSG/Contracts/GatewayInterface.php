<?php


namespace App\Services\Common\MSG\Contracts;

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
     * @return mixed
     */
    public function send(PhoneNumberInterface $to, MessageInterface $message);
}