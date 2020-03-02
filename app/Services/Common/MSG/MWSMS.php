<?php


namespace App\Services\Common\MSG;

use GuzzleHttp\Client;

use Exception;
use Illuminate\Support\Facades\Log;

/**
 * 梦网短信接口
 *
 * Class MWSMS
 * @auth hsu lay
 * @time 20200210
 * @package App\Services\Common\MSG
 */
class MWSMS
{
    protected $config;

    protected $timestamp;

    public function __construct()
    {
        $this->config = config('sms.MW');

        $this->timestamp = date('mdHis');
    }

    /**
     * 单条短信
     * 发送短信内容至单个手机号。每次只能将短信内容发送至一个手机号码，否则接口返回失败。
     *
     * @param $content
     * @param $phone
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function single_send($content, $phone)
    {
        $config = $this->config;

        $apiStr = 'single_send';

        $form_params = [
            'userid' => $config['account'],
            'pwd' => $this->encryption(),
            'mobile' => $phone,
            'content' => $this->TO_GBK($content),
            'timestamp' => $this->timestamp
        ];

        return $this->send($config, $apiStr, $form_params);
    }

    /**
     * 相同内容群发接口
     * 发送相同的短信内容至多个不同的手机号。每次最多可将相同的短信内容发送至1000个手机号码，否则接口返回失败
     *
     * @param $content
     * @param $phone
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function batch_send($content, $phone)
    {
        $config = $this->config;

        $apiStr = 'batch_send';

        $form_params = [
            'userid' => $config['account'],
            'pwd' => $this->encryption(),
            'mobile' => implode(',', $phone),
            'content' => $this->TO_GBK($content),
            'timestamp' => $this->timestamp
        ];
        return $this->send($config, $apiStr, $form_params);
    }

    /**
     * 个性化群发接口
     * 发送不同的短信内容至多个不同手机号。每次最多可发送100个手机号码的个性化短信内容至对应的手机号码，否则接口返回失败
     *
     * @param $mutimt
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function multi_send($mutimt)
    {
        $config = $this->config;

        $apiStr = 'multi_send';

        foreach($mutimt as &$v){
            $v['content'] = $this->TO_GBK($v['content']);
        }

        $form_params = [
            'userid' => $config['account'],
            'pwd' => $this->encryption(),
            'timestamp' => $this->timestamp,
            'multimt' => $mutimt
        ];

        return $this->send($config, $apiStr, $form_params);
    }

    /**
     * 查询余额
     * 查询剩余总金额或剩余短信总条数。如计费类型为金额计费，可只取剩余总金额的参数值，如计费类型为条数计费，可只取剩余短信总条数的参数值
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getBalance()
    {
        $config = $this->config;

        $apiStr = 'get_balance';

        $form_params = [
            'userid' => $config['account'],
            'pwd' => $this->encryption(),
            'timestamp' => $this->timestamp
        ];
        return $this->send($config, $apiStr, $form_params);
    }

    /**
     * 请求获取上行
     * 获取手机用户发送给通讯服务提供商的短信。为确保能够快速及时获取到上行，建议可在调用接口后判断接口是否有上行返回，
     * 若有返回，则需要一直获取，直到接口返回无数据时，延时5秒，然后再次重复前面的获取和判断操作
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getMo()
    {
        $config = $this->config;

        $apiStr = 'getMo';

        $form_params = [
            'userid' => $config['account'],
            'pwd' => $this->encryption(),
            'timestamp' => $this->timestamp
        ];
        return $this->send($config, $apiStr, $form_params);
    }

    /**
     * 获取状态报告接口
     * 获取运营商提供的每条短信的发送状态信息。为确保能够快速及时获取到状态报告，建议可在调用接口后判断接口是否有状态报告返回，
     * 若有返回，则需要一直获取，直到接口返回无数据时，延时5秒，然后再次重复前面的获取和判断操作。
     * 在“状态报告”的包结构“rpts”中，若是短短信，“pknum”与“pktotal”两个字段的值都为1
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getRpt()
    {
        $config = $this->config;

        $apiStr = 'getRpt';

        $form_params = [
            'userid' => $config['account'],
            'pwd' => $this->encryption(),
            'timestamp' => $this->timestamp
        ];
        return $this->send($config, $apiStr, $form_params);
    }

    /**
     * @param array $config
     * @param string $apiStr
     * @param array $form_params
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private static function send(array $config, string $apiStr, array $form_params)
    {
        if($config['debug']){
            Log::info($form_params);
            return true;
        }

        $client = new Client(['base_uri' => $config['url']['mult']]);
        try{
            $response = $client->request('POST', $apiStr, [ 'json' => $form_params ]);
            $data = $response->getBody()->getContents();
            return json_decode($data);
        } catch (Exception $e) {
            return $e->getMessage();
        }
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