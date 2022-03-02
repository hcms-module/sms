<?php
declare(strict_types=1);
//[
//    'demo' => [
//        [
//            'setting_key' => 'demo_string',
//            'setting_description' => '字符串类型配置示例',
//            'setting_value' => '示例字符串',
//            'type' => 'string',
//        ]
//    ]
//];
return [
    'sms' => [
        [
            'setting_key' => 'sms_platform_id',
            'setting_description' => '短信平台id，具体可以看 https://github.com/overtrue/easy-sms',
            'setting_value' => 'aliyun',
            'type' => 'string',
        ],
        [
            'setting_key' => 'sms_platform_setting',
            'setting_description' => '短信平台相关配置',
            'setting_value' => '{}',
            'type' => 'json',
        ]
    ]
];