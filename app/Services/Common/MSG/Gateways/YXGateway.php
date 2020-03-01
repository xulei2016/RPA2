<?php


namespace App\Services\Common\MSG\Gateways;

use App\Services\Common\MSG\Contracts\MessageInterface;
use App\Services\Common\MSG\Contracts\PhoneNumberInterface;
use App\Services\Common\MSG\Exceptions\GatewayErrorException;
use App\Services\Common\MSG\Support\Config;
use App\Services\Common\MSG\Traits\HasHttpRequest;

/**
 * Class YXGateway
 *
 * @package App\Services\Common\MSG\Gateways
 */
class YXGateway extends Gateway
{
    use HasHttpRequest;

    protected $config;

    protected $timestamp;

    public function __construct(array $config)
    {
        parent::__construct($config);

        $this->config = new config($config);

        $this->timestamp = date('mdHis');
    }

    /**
     * @param PhoneNumberInterface $to
     * @param MessageInterface $message
     * @return array|mixed
     * @throws GatewayErrorException
     */
    public function send(PhoneNumberInterface $to, MessageInterface $message)
    {
        $apiStr = '';

        $msg = $message->getContent($this);

        $params = [
            'CorpID' => $this->config['account'],
            'Pwd' => $this->config['password'],
            "Mobile" => $to->getNumber(),
            "Content" => $this->TO_GBK($msg),
            "Cell" => '',
            "SendTime" => ''
        ];

        $result = $this->unwrapResponse($this->getHttpClient($this->getBaseOptions())->request('post', $apiStr, [
            'headers' => ['Accept' => 'application/json'],
            'form_params' => $params
        ]));

        if (0 != $result) {
            throw new GatewayErrorException($this->config['status'][$result] ?? '', $result, [$result]);
        }

        return compact('result');
    }

    /**
     * @param $content
     * @return bool|false|string
     */
    protected function TO_GBK($content)
    {
        return iconv("utf-8","gb2312",$content);
    }
}