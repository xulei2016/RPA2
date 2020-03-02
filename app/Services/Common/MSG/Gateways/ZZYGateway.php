<?php


namespace App\Services\Common\MSG\Gateways;

use App\Services\Common\MSG\Contracts\MessageInterface;
use App\Services\Common\MSG\Contracts\PhoneNumberInterface;
use App\Services\Common\MSG\Exceptions\GatewayErrorException;
use App\Services\Common\MSG\Support\Config;
use App\Services\Common\MSG\Traits\HasHttpRequest;

/**
 * Class ZZYGateway
 * @package App\Services\Common\MSG\Gateways
 */
class ZZYGateway extends Gateway
{
    use HasHttpRequest;

    protected $config;

    protected $timestamp;

    /**
     * ZZYGateway constructor.
     *
     * @param array $config
     */
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
            'id' => $this->config['account'],
            'pwd' => $this->config['password'],
            'to' => $to->getNumber(),
            'content' => $this->TO_GBK($msg),
        ];

        $result = $this->unwrapResponse($this->getHttpClient($this->getBaseOptions())->request('post', $apiStr, [
            'headers' => ['Accept' => 'application/json'],
            'form_params' => $params
        ]));

        $result = explode('/',(string)$result);

        if (0 != $result) {
            throw new GatewayErrorException($this->config['status'][$result[0]] ?? '', $result[0], [$result]);
        }

        return [
            'result' => $result[0],
            'data' => $result
        ];
    }

    /**
     * @param $content
     * @return bool|false|string
     */
    protected function TO_GBK($content)
    {
        return urlencode(iconv("utf-8","gb2312",$content));
    }
}