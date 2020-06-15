<?php


namespace App\Services\Common\MSG;

use App\Services\Common\MSG\Contracts\MessageInterface;
use App\Services\Common\MSG\Contracts\PhoneNumberInterface;
use App\Services\Common\MSG\Exceptions\NoGatewayAvailableException;
use Illuminate\Support\Facades\Log;

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
     * @param bool $debug
     * @return array
     * @throws NoGatewayAvailableException
     */
    public function send(PhoneNumberInterface $to, MessageInterface $message, array $gateways = [], $params = [], $debug = false)
    {
        $results = [];

        $sendSuccess = false;

        foreach ($gateways as $gateway => $config) {
            try {

                $result = $debug ? Log::info('DEBUG MODAL', [$to->getNumber(), $message->getContent()])
                    : $this->sms->gateway($gateway)->send($to, $message, $params);

                $results[$gateway] = [
                    'gateway' => $gateway,
                    'status' => self::STATUS_SUCCESS,
                    'result' => $result,
                ];

                $sendSuccess = true;
                
                break;

            } catch (\Exception $e) {
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

        if (!$sendSuccess) {
            throw new NoGatewayAvailableException($results);
        }

        return $results;
    }

}