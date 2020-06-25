<?php

/*
 * YUserModel Code
 *
 * @Created: 2020-06-21 09:42:49
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\constants\model;

use app\constants\extend\BaseCode;

class YUserCode extends BaseCode {

    // 用户状态：审核中
    const STATUS__AUTHING = 0;

    // 用户状态：启动
    const STATUS__ENABLED = 1;

    // 用户状态：禁用
    const STATUS__DISABLED = 2;

    // 映射
    public static $map = [
        'status' => [
            self::STATUS__AUTHING  => '审核中',
            self::STATUS__ENABLED  => '启动',
            self::STATUS__DISABLED => '禁用',
        ],
    ];

}