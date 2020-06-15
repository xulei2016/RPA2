<?php
/**
 * 百度api参数配置
 */
return [
    'ak' => 'MrEUOWVKDgUW0lopooN6R4jT6gcCbUu3',

    'APPID' => '18265729',
    'API_KEY' => '6Hh3Y0SYi1CU3grUrbqDSZRf',
    'SECRET_KEY' => 'bIqFbUkAa4WDSm4Hn1Qam7hjPDD3RF6U',
    'token_url' => 'https://aip.baidubce.com/oauth/2.0/token',

    // ip定位
    'ip_location' => [
        'url' => 'http://api.map.baidu.com/location/ip',

        'status' => [
            '0' => '正常',
            '1' => '服务器内部错误',
            '10' => '上传内容超过8M',
            '101' => 'AK参数不存在',
            '102' => 'Mcode参数不存在，mobile类型mcode参数必需',
            '200' => 'APP不存在，AK有误请检查再重试',
            '201' => 'APP被用户自己禁用，请在控制台解禁',
            '202' => 'APP被管理员删除',
            '203' => 'APP类型错误',
            '210' => 'APP IP校验失败',
            '211' => 'APP SN校验失败',
            '220' => 'APP Referer校验失败',
            '230' => 'APP Mcode码校验失败',
            '240' => 'APP 服务被禁用',
            '250' => '用户不存在',
            '251' => '用户被自己删除',
            '260' => '服务不存在',
            '261' => '服务被禁用',
            '301' => '永久配额超限，限制访问',
            '302' => '天配额超限，限制访问',
            '401' => '当前并发量已经超过约定并发配额，限制访问',
            '402' => '当前并发量已经超过约定并发配额，并且服务总并发量也已经超过设定的总并发配额，限制访问',
        ]
    ],

    //身份证识别
    'idCard_OCR' => [
        'url' => 'https://aip.baidubce.com/rest/2.0/ocr/v1/idcard',
        //配置
        'detect_direction' => true, //是否检测图像旋转角度
        'detect_risk' => false, //是否开启身份证风险类型
        'detect_photo' => false, //是否检测头像内容
        'detect_rectify' => true, //是否进行完整性校验
    ],
    //银行卡识别
    'bankCard_OCR' => [
        'url' => 'https://aip.baidubce.com/rest/2.0/ocr/v1/bankcard',
        //配置
        'detect_direction' => false, //是否检测图像旋转角度
    ]
];