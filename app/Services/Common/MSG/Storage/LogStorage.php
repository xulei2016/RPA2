<?php


namespace App\Services\Common\MSG\Storage;

use App\Services\Common\MSG\Contracts\LogInterface;
use App\Services\Common\MSG\Contracts\MessageInterface;
use App\Services\Common\MSG\Contracts\PhoneNumberInterface;
use App\Services\Common\MSG\Notify\ErrNotify;
use App\Services\Common\MSG\Support\Config;
use DB;
use Illuminate\Support\Facades\Log;

/**
 * Class LogStorage
 *
 * @package App\Services\Common\MSG\Storage
 */
class LogStorage implements LogInterface
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var array params
     */
    protected $params;

    /**
     * @var
     */
    protected $gateway;

    /**
     * @var int
     */
    protected $code = 0;

    /**
     * @var int
     */
    protected $status = 0;

    /**
     * @var string
     */
    protected $response = '';

    /**
     * @var
     */
    protected $errMsg;

    /**
     * @var string
     */
    private $default_errNotify_to_role = 'smsManager';

    /**
     * @var bool
     */
    private $needErrNotify = false;

    /**
     * LogStorage constructor.
     *
     * @param Config $config
     * @param array $params
     */
    public function __construct(Config $config, array $params)
    {
        $this->config = $config;

        $this->params = $params;
    }

    /**
     * @param PhoneNumberInterface $to
     * @param MessageInterface $message
     * @return mixed
     */
    public function beforeSend(PhoneNumberInterface $to, MessageInterface $message)
    {
        return $this->addLog($to->getNumber(), $message->getContent());
    }

    /**
     * @param $id
     * @param $results
     * @return mixed
     */
    public function afterSend($id, $results)
    {
        $err = [];

        foreach ($results as $k => $result) {

            $this->gateway = $k;

            if ('failure' === $result['status'] && isset($result['exception'])) {
                $err[] = $result;
                $results[$k]['code'] = $result['exception']->getCode();

                $this->failureResponse($result);
            } else {
                $this->successResponse($result);
            }

            if ((!($this->config['debug'] ?: false)) && ('failure' === $result['status'])) unset($results[$k]['exception']);

            $this->updateLog($id, $result);

        }

        if($this->needErrNotify)
            (new ErrNotify())->notify($this->config->get('errNotify_to_role', $this->default_errNotify_to_role), $err);

        return $results;
    }

    /**
     * @param $phone
     * @param $msg
     * @return mixed
     */
    protected function addLog($phone, $msg)
    {
        $logContent = $this->getSerializeParams($phone, $msg);
        return 'file' === $this->get('logType', 'db') ? $this->addFileLog($logContent) : $this->addDBLog($logContent);
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $config = $this->config;

        if (isset($config[$key])) {
            return $config[$key];
        }

        return $default;
    }

    /**
     * @param $logContent
     * @return mixed
     */
    protected function addFileLog($logContent)
    {
        return Log::info('sms', $logContent);
    }

    /**
     * @param $logContent
     * @return mixed
     */
    protected function addDBLog($logContent)
    {
        return DB::table('sys_sms_logs')->insertGetId($logContent);
    }

    /**
     * @param $id
     * @param $result
     * @return string
     */
    protected function updateLog($id = null, $result)
    {
        try {
            if ('db' === $this->config->get('logType') && $id){
                DB::table('sys_sms_send_logs')->insert([
                    'log_id' => $id,
                    'type' => $this->gateway,
                    'errMsg' => $this->errMsg,
                    'return' => $this->code,
                    'status' => $this->status,
                    'response' => json_encode($this->response),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            } else{
                Log::info($result['status'], $result);
            }

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $result
     */
    protected function failureResponse($result)
    {
        $this->needErrNotify = true;
        $this->status = 2;
        $this->code = $result['exception']->getCode();
        $this->errMsg = $result['exception']->getMessage();
        $this->response = $result['exception']->raw ?? '' ;
    }

    /**
     * @param $result
     */
    protected function successResponse($result)
    {
        $this->status = 1;
        $this->code = $result['result']['result'];
        $this->response = $result['result'];
    }

    /**
     * @param $phone
     * @param $msg
     * @return array
     */
    protected function getSerializeParams($phone, $msg)
    {
        return [
            'api' => $this->params['api'] ?? '',
            'phone' => $phone ?: '',
            'patchID' => $this->params['patchID'] ?? '',
            'content' => $msg,
            'created_at' => date('Y-m-d H:i:s', time()),
        ];
    }

}