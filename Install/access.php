<?php
declare(strict_types=1);
// 菜单层级最多三级
//[
//    [
//        'parent_access_id' => 0,
//        'access_name' => '示例',
//        'uri' => 'demo/demo/none',
//        'params' => '',
//        'sort' => 100,
//        'is_menu' => 1,
//        'menu_icon' => 'el-icon-data-analysis',
//        'children' => []
//    ]
//]
return [
    [
        'parent_access_id' => 0,
        'access_name' => '短信sms',
        'uri' => 'sms/sms',
        'params' => '',
        'sort' => 100,
        'is_menu' => 1,
        'menu_icon' => 'line-icon-pinglun_3',
        'children' => [
            [
                'access_name' => '短信设置',
                'uri' => 'sms/sms/setting',
                'params' => '',
                'sort' => 100,
                'is_menu' => 1
            ],
            [
                'access_name' => '发送记录',
                'uri' => 'sms/sms',
                'params' => '',
                'sort' => 100,
                'is_menu' => 1
            ]
        ]
    ]
];