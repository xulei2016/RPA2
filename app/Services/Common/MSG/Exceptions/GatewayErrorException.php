<?php


namespace App\Services\Common\MSG\Exceptions;

/**
 * Class GatewayErrorException
 * @package App\Services\Common\MSG\Exceptions
 */
class GatewayErrorException extends Exception
{
    /**
     * @var array
     */
    public $raw = [];

    /**
     * GatewayErrorException constructor.
     *
     * @param string $message
     * @param int $code
     * @param array $raw
     */
    public function __construct($message, $code, array $raw = [])
    {
        parent::__construct($message, intval($code));

        $this->raw = $raw;
    }
}