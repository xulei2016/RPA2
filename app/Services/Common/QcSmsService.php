<?php
namespace App\Services\Common;

use Qcloud\Sms\SmsMultiSender;
use Qcloud\Sms\SmsSingleSender;
class QcSmsService
{
    protected $smsServerSingle;
    protected $smsServerMulti;
    protected $sms;
    protected $templateId;

    public function __construct(){
        $this->sms = config('sms.TX');
        $this->smsServerSingle = new SmsSingleSender($this->sms['app_id'],$this->sms['app_key']);
        $this->smsServerMulti = new SmsMultiSender($this->sms['app_id'],$this->sms['app_key']);
        $this->templateId = 216192;
    }

    /**
     * 单发
     * @param $phone
     * @param $msg
     * @return array
     */
    public function single($phone,$msg)
    {
        try{
            $result = $this->smsServerSingle->sendWithParam(
                '86',
                $phone,
                $this->templateId,
                [$msg],
                $this->sms['smsSign'],
                "",
                ""
            );

            $re = [
                'state' => true,
                'data' => json_decode($result)
            ];
        }catch(\Exception $exception){
            $re = [
                'state' => false,
                'data' => $exception
            ];
        }

        return $re;
    }

    /**
     * 群发
     * @param $phone
     * @param $msg
     * @return array
     */
    public function multi($phone,$msg)
    {
        try{
            $result = $this->smsServerMulti->sendWithParam(
                '86',
                $phone,
                $this->templateId,
                $msg,
                $this->sms['smsSign'],
                "",
                ""
            );

            $re = [
                'state' => true,
                'data' => json_decode($result)
            ];
        }catch(\Exception $exception){
            $re = [
                'state' => false,
                'data' => $exception
            ];
        }

        return $re;
    }
}