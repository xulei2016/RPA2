<?php


namespace App\Services\Common\MSG;

use App\Services\Common\MSG\Contracts\MessageInterface;
use App\Services\Common\MSG\Contracts\PhoneNumberInterface;
use App\Services\Common\MSG\Exceptions\NoGatewayAvailableException;

/**
 * Class Messenger
 * @package App\Services\Common\MSG
 */
class Messenger
{
    const STATUS_SUCCESS = 'success';

    const STATUS_FAILURE = 'failure';

    protected $sms;

    /**
     * Messenger constructor.
     * @param SMS $sms
     */
    public function __construct(SMS $sms)
    {
        $this->sms = $sms;
    }

    /**
     * @param PhoneNumberInterface $to
     * @param MessageInterface $message
     * @param array $gateways
     * @return array
     * @throws NoGatewayAvailableException
     * @throws Exceptions\InvalidArgumentException
     */
    public function send(PhoneNumberInterface $to, MessageInterface $message, array $gateways = [])
    {
        $results = [];

        $sendSuccess = false;

        foreach ($gateways as $gateway => $config) {
            try {
                $results[$gateway] = [
                    'gateway' => $gateway,
                    'status' => self::STATUS_SUCCESS,
                    'result' => $this->sms->gateway($gateway)->send($to, $message, $config),
                ];

                $sendSuccess = true;

            } catch (\Exception $e) {
                dd($e->getMessage());
                $results[$gateway] = [
                    'gateway' => $gateway,
                    'status' => self::STATUS_FAILURE,
                    'exception' => $e,
                ];
            } catch (\Throwable $e) {
                $results[$gateway] = [
                    'gateway' => $gateway,
                    'status' => self::STATUS_FAILURE,
                    'exception' => $e,
                ];
            }
        }

        dd($results);

        if (!$sendSuccess) {
            throw new NoGatewayAvailableException($results);
        }

        return $results;
    }

}