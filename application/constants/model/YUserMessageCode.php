<?php

/*
 * YUserMessageModel Code
 *
 * @Created: 2020-07-15 22:06:00
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\constants\model;

use app\constants\extend\BaseCode;

class YUserMessageCode extends BaseCode {

    // 消息状态：未读
    const STATUS__UNREAD = 0;

    // 消息状态：已读
    const STATUS__READ = 1;

    // 映射
    public static $map = [
        'status' => [
            self::STATUS__UNREAD => '未读',
            self::STATUS__READ   => '已读',
        ],
    ];

}