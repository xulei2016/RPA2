<?php


namespace App\Services\Common\MSG\Gateways;

use App\Services\Common\MSG\Contracts\PhoneNumberInterface;
use App\Services\Common\MSG\Contracts\MessageInterface;
use App\Services\Common\MSG\Exceptions\GatewayErrorException;
use App\Services\Common\MSG\Support\Config;
use App\Services\Common\MSG\Traits\HasHttpRequest;
use Exception;
use GuzzleHttp\Client;

class MWGateway extends Gateway
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
     * 相同内容群发接口
     * 发送相同的短信内容至多个不同的手机号。每次最多可将相同的短信内容发送至1000个手机号码，否则接口返回失败
     *
     * @param PhoneNumberInterface $to
     * @param MessageInterface $message
     * @param Config $config
     * @return array|mixed
     * @throws GatewayErrorException
     */
    public function send(PhoneNumberInterface $to, MessageInterface $message, Config $config)
    {
        $apiStr = 'single_send';

        $msg = $message->getContent($this);

        $params = [
            'userid' => $config['account'],
            'pwd' => $this->encryption(),
            'mobile' => $to->getNumber(),
            'content' => $this->TO_GBK($msg),
            'timestamp' => $this->timestamp
        ];

        $base_url = $config->get('url');
        $url = $base_url['mult'] . $apiStr;

        $result = $this->request('post', $url, [
            'headers' => ['Accept' => 'application/json'],
            'json' => $params,
        ]);

        dd($result);

        if (0 != $result['result']) {
            throw new GatewayErrorException($result['errmsg'], $result['result'], $result);
        }

        return $result;
    }

    /**
     * @param PhoneNumberInterface $to
     * @param MessageInterface $message
     * @param Config $config
     * @return array
     * @throws GatewayErrorException
     */
    public function batch_send(PhoneNumberInterface $to, MessageInterface $message, Config $config)
    {
        $apiStr = 'batch_send';

//        $data = $message->getData($this);

        $msg = $message->getContent($this);

        $params = [
            'userid' => $config['account'],
            'pwd' => $this->encryption(),
            'mobile' => $to->getNumber(),
            'content' => $this->TO_GBK($msg),
            'timestamp' => $this->timestamp
        ];

        $url = $config->get('base_url').$apiStr;

        $result = $this->request('post', $url, [
            'headers' => ['Accept' => 'application/json'],
            'json' => $params,
        ]);

        if (0 != $result['result']) {
            throw new GatewayErrorException($result['errmsg'], $result['result'], $result);
        }

        return $result;
    }


    /**
     * @return mixed|string
     */
    protected function encryption()
    {
        $config = $this->config;
        $pwd = '';

        if (1 == $config['mode']) {   //明文
            $pwd = $config['password'];
        } elseif (2 == $config['mode']) {  //加密
            $pwd = $config['account'] . '00000000' . $config['password'] . $this->timestamp;
            $pwd = MD5($pwd);
        }

        return $pwd;
    }

    /**
     * @param $content
     * @return bool|false|string
     */
    protected function TO_GBK($content)
    {
        return urlencode(iconv('UTF-8', 'GBK', $content));
    }
}