<?php

return [

    /*
    |--------------------------------------------------------------------------
    | type 验证码识别平台
    |--------------------------------------------------------------------------
    |
    |   默认验证码识别平台 云打码（yundama），尖叫（jianjiao）
    |
    */

    'default' => 'yundama',


    /*
    |--------------------------------------------------------------------------
    | 验证码识别平台账户信息配置
    |--------------------------------------------------------------------------
    |
    | 验证码识别地址，mult默认的验证码识别发送接口地址
    | 验证码识别账户、密码、密钥配置信息等
    |
    |
    */
    'yundama' => [
        'url' => [
            'mult' => 'http://api.yundama.com/api.php',
        ],
        'USERNAME' => 'haqhrjgcb',
        'PASSWORD' => 'H@qhrjgcb9772,.',
        'APPID' => '1',
        'APPKEY' => '22cc5376925e9387a23cf797cb9ba745',
    ],

    'jianjiao' => [
        'url' => [
            'mult' => 'http://apigateway.jianjiaoshuju.com/api/v_1/fzyzm.html',
        ],
        'APPCODE' => 'A8AD2472FA7AF44A7C393EF3A8A55B13',
        'APPKEY' => 'AKID32f578bd65497b531f92a26419d1a7a7',
        'APPSECRET' => 'e5fe0353fc8b878fad9d00f3514024a8',
    ],

    'chaorenyun' => [
        'url' => [
            'mult' => 'http://api2.sz789.net:88/RecvByte.ashx',
        ],
        'USERNAME' => 'haqhjrkjb',
        'PASSWORD' => 'H@qh9772,.',
    ],
];