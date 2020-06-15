<?php

namespace App\Http\Controllers\base;

use App\Models\Index\Common\RpaCaptcha;
use App\Services\Common\QcSmsService;

class BaseWebController extends BaseController
{
    /**
     * 发送验证码
     * @param $phone
     * @param $type
     * @return bool
     */
    public function smsCode($phone,$type)
    {
        $qc = new QcSmsService();
        $code = mt_rand(100000,999999);
        $res = $qc->single($phone,$code);
        if($res['state'] == true && !isset($res['data']->errCode) && isset($res['data']->result) && $res['data']->result == 0){
            $captcha = RpaCaptcha::where('phone',$phone)->first();
            if($captcha){
                $update = [
                    'code' => $code,
                    'count' => $captcha->count + 1
                ];
                RpaCaptcha::where('phone',$phone)->update($update);
            }else{
                $add = [
                    'phone' => $phone,
                    'code' => $code,
                    'count' => 1,
                    'type' => $type
                ];
                RpaCaptcha::create($add);
            }
            return true;
        }else{
            return false;
        }
    }
}
