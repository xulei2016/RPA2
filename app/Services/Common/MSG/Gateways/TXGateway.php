<?php

namespace App\Services\Common\MSG\Gateways;

use App\Services\Common\MSG\Contracts\MessageInterface;
use App\Services\Common\MSG\Contracts\PhoneNumberInterface;
use App\Services\Common\MSG\Exceptions\GatewayErrorException;
use App\Services\Common\MSG\Support\Config;
use Qcloud\Sms\SmsMultiSender;
use Qcloud\Sms\SmsSingleSender;

/**
 * Class TXGateway
 *
 * @package Overtrue\EasySms\Gateways
 */
class TXGateway extends Gateway
{
    /**
     * @var SmsSingleSender
     */
    protected $smsServerSingle;

    /**
     * @var SmsMultiSender
     */
    protected $smsServerMulti;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var int
     */
    protected $templateId;

    /**
     * TXGateway constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        $this->config = new config($config);
        
        $this->smsServerSingle = new SmsSingleSender($this->config['app_id'], $this->config['app_key']);
        $this->smsServerMulti = new SmsMultiSender($this->config['app_id'], $this->config['app_key']);
        $this->templateId = 216192;
    }

    /**
     * 单发
     * @param PhoneNumberInterface $to
     * @param MessageInterface $message
     * @return array
     * @throws GatewayErrorException
     */
    public function send(PhoneNumberInterface $to, MessageInterface $message)
    {
        $result = $this->smsServerSingle->sendWithParam(
            86,
            $to->getNumber(),
            $this->templateId,
            [$message->getContent($this)],
            $this->config['smsSign'],
            "",
            ""
        );

        $result = json_decode($result, true);

        if (0 != $result['result']) {
            throw new GatewayErrorException($this->config['status'][$result['result']] ?? '', $result['result'], [$result]);
        }

        return $result;
    }

    /**
     * 群发
     * @param PhoneNumberInterface $to
     * @param MessageInterface $message
     * @return array
     */
    public function multi(PhoneNumberInterface $to, MessageInterface $message)
    {
        try {
            $result = $this->smsServerMulti->sendWithParam(
                '86',
                $to,
                $this->templateId,
                $message,
                $this->config['smsSign'],
                "",
                ""
            );

            $re = [
                'state' => true,
                'data' => json_decode($result)
            ];
        } catch (\Exception $exception) {
            $re = [
                'state' => false,
                'data' => $exception
            ];
        }

        return $re;
    }
}
