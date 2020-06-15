<?php


namespace App\Services\Common;

use DB;

/**
 * Class SMSMsg
 * @package App\Services\Common\MSG
 */
class SMSMsg extends MSGInterface
{
    protected $content;

    private $config;

    private $phone;

    private $param;

    private $callBack;

    static protected $tries;

    /**
     * SMSMsg constructor.
     * @param null $config
     */
    public function __construct($config = null)
    {
        $this->config = $config ?? config('sms.MW');
    }

    /**
     * 【
     *  $phone:array && $content:string  普通群发短信
     *  isset($param['mutimt']) && !empty($param['mutimt'])  个性化群发短信
     *  $phone:string && $content:string  单发短信
     *  callBack 特殊接口
     * 】
     *
     * @param string $content
     * @param string $phone
     * @param array $param
     * @param string $callBack
     * @return bool|mixed|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function init($content = '', $phone = '', array $param = [], string $callBack = '')
    {
        $this->phone = $phone;
        $this->param = $param;
        $this->content = $content;
        $this->callBack = $callBack;

//        $this->phone_check();

        $this->unique_phone();

        return $this->send();
    }

    /**
     * @return bool|mixed|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function send()
    {
        return self::MW_SMS();
    }

    /**
     * 梦网短信接口
     *
     * @return bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function MW_SMS()
    {
        $res = false;
        $content = $this->content;
        $phone = $this->phone;
        $param = $this->param;
        $callBack = $this->callBack;

        //个性化短信发送
        if (isset($param['mutimt']) && !empty($param['mutimt'])) {
            if (count($param['mutimt']) > 100) {
                return $this->throw(500, '一次最多处理100条短信！');
            }
            $res = (new MWSMS($this->config))->multi_send($param['mutimt']);

            //普通群发短信
        } elseif (is_array($phone) && is_string($content)) {
            if (count($phone) > 1000) {
                return $this->throw(500, '一次最多处理1000条短信！');
            }
            $res = (new MWSMS($this->config))->batch_send($content, $phone);

            //普通单发短信
        } elseif (is_string($phone) && is_string($content) && !empty($phone) && !empty($content)) {
            $res = (new MWSMS($this->config))->single_send($content, $phone);

            //指定其他请求方法
        } elseif (!empty($callBack)) {
            if (method_exists(new MWSMS($this->config), $callBack)) {
                $res = (new MWSMS($this->config))->$callBack($content, $phone);
            }
        }

        if ($res) {
            if (isset($res->result)) {
                if (0 !== $res->result) {
                    $config = $this->config;
                    $error_msg = isset($config['MW']['status'][$res->result]) ? $config['MW']['status'][$res->result] : '未知错误-' . $res->result;
                    return $this->throw(500, 'fail', $error_msg);
                }
                return [
                    'code' => 200,
                    'info' => 'success',
                    'data' => [
                        'result' => $res->result,
                        'desc' => $res->desc,
                        'msgid' => $res->msgid,
                        'custid' => $res->custid
                    ],
                ];
            } elseif (is_string($res)) {
                return $this->throw(500, $res);
            }
        }
        return $this->throw(404, '无效的请求！');
    }

    ///////////////////////////////////////////梦网短信end////////////////////////////////////////////////

    /**
     * @return array|bool
     */
    protected function phone_check()
    {
        $phones = $this->phone;
        $param = $this->param;

        if (isset($param['mutimt'])) {
            foreach ($param['mutimt'] as $mutimt) {
                if (!preg_match("/^1\d{10}$/", $mutimt['mobile'])) {
                    return $this->throw(500, '存在无效的手机号！');
                    break;
                }
            }
        } elseif (is_array($phones)) {
            foreach ($phones as $phone) {
                if (!preg_match("/^1\d{10}$/", $phone)) {
                    return $this->throw(500, '存在无效的手机号！');
                    break;
                }
            }
        } elseif (is_string($phones)) {
            if (!preg_match("/^1\d{10}$/", $phones)) {
                return $this->throw(500, '存在无效的手机号！');
            }
        }
        return true;
    }

    /**
     * @param $data
     * @return mixed
     */
    private function save($data)
    {
        return DB::table('sys_sms_logs')->insert($data);
    }

    /**
     * @return void
     */
    protected function unique_phone()
    {
        if (is_array($this->phone)) {
            $this->phone = array_unique($this->phone);
        }
    }

    /**
     * @param int $code
     * @param string $error
     * @param array $data
     * @return array
     */
    protected function throw($code = 500, $error = '', $data = [])
    {
        return [
            'code' => $code,
            'msg' => $error,
            'data' => $data
        ];
    }


}