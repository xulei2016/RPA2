<?php


namespace App\Services\Common\MSG;

use Exception;
use App\Services\Common\MSG\MWSMS;
use App\Services\Common\MSGInterface;

use DB;

/**
 * Class SMSMsg
 * @package App\Services\Common\MSG
 */
class SMSMsg extends MSGInterface
{
    protected $content;

    private $config;

    private $type;

    private $phone;

    private $param;

    private $callBack;

    static protected $tries;

    /**
     * @param string $content
     * @param string $phone
     * @param string $type
     * @param array $param
     * @param string $callBack
     * @return bool|mixed|void
     */
    public function init($content = '', $phone = '', string $type = '', array $param = [], string $callBack = '')
    {
        $this->type = $type;
        $this->phone = $phone;
        $this->param = $param;
        $this->content = $content;
        $this->callBack = $callBack;
        $this->config = config('sms');

//        $this->phone_check();

        $this->unique_phone();

        return $this->send();
    }

    /**
     * @return bool|mixed|void
     */
    protected function send()
    {
        $config = $this->config;
        $type = $this->type ? strtoupper($this->type) : $config['default'] ;

        if(is_array($config['list'])){
            $func = $type.'_SMS';
            return self::$func();
        }

        return $this->throw('500', '无效的请求!');
    }

    protected function TX_SMS()
    {
        //TODO
        return false;
    }

    protected function ZZY_SMS()
    {
        //TODO
        return false;
    }

    protected function YX_SMS()
    {
        //TODO
        return false;
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

        if (isset($param['mutimt']) && !empty($param['mutimt'])) {
            if(count($param['mutimt']) > 100){
                return $this->throw(500, '一次最多处理100条短信！');
            }
            $res = (new MWSMS)->multi_send($param['mutimt']);
        } elseif (is_array($phone) && is_string($content)) {
            if(count($phone) > 1000){
                return $this->throw(500, '一次最多处理1000条短信！');
            }
            $res = (new MWSMS)->batch_send($content, $phone);
        } elseif (is_string($phone) && is_string($content) && !empty($phone) && !empty($content)) {
            $res = (new MWSMS)->single_send($content, $phone);
        }elseif (!empty($callBack)){
            if(method_exists (new MWSMS, $callBack)){
                $res = (new MWSMS)->$callBack($content, $phone);
            }
        }

        if($res){
            if(isset($res->result)){
                if(0 !== $res->result){
                    $config = $this->config;
                    $error_msg = isset($config['MW']['status'][$res->result]) ? $config['MW']['status'][$res->result] : '未知错误-'.$res->result ;
                    return $this->throw(500, 'fail', $error_msg);
                }
                return $res;
            } elseif(is_string($res)){
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