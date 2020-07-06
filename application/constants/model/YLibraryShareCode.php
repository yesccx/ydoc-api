<?php

/*
 * YLibraryShareModel Code
 *
 * @Created: 2020-07-04 10:07:07
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\constants\model;

use app\constants\extend\BaseCode;

class YLibraryShareCode extends BaseCode {

    // 成员状态：审核中（初始状态）
    const STATUS__NORMAL = 0;

    // 成员状态：启用
    const STATUS__ENABLED = 1;

    // 成员状态：禁用
    const STATUS__DISABLED = 2;

    // 是否受保护：否
    const IS_PROTECTED__NO = 0;

    // 是否受保护：是
    const IS_PROTECTED__YES = 1;

    // 映射
    public static $map = [
        'status'       => [
            self::STATUS__NORMAL   => '审核中',
            self::STATUS__ENABLED  => '启用',
            self::STATUS__DISABLED => '禁用',
        ],
        'is_protected' => [
            self::IS_PROTECTED__NO  => '非保护',
            self::IS_PROTECTED__YES => '受保护',
        ],
    ];

}