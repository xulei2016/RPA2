<?php

return [

    'captcha_lifetime' =>  600, //验证码有效期（秒）
    'renewal_day' => 30,         //可以续签时间（天）
    'secret_key' => 'H@qh-mediator', //生成文件夹的秘钥

    //短信发送
    'sms_func' => 'yx_sms',  //短信平台  可选：zzy_sms,yx_sms
    'file_root' => '/app/mediator'
];