<?php

/*
 * YLibraryModel Code
 *
 * @Created: 2020-06-20 20:58:07
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\constants\model;

use app\constants\extend\BaseCode;

class YLibraryCode extends BaseCode {

    // 文档库状态：正常
    const STATUS__NORMAL = 1;

    // 文档库状态：归档
    const STATUS__PERMANENT = 2;

    // 映射
    public static $map = [
        'status' => [
            self::STATUS__NORMAL    => '正常',
            self::STATUS__PERMANENT => '归档',
        ],
    ];

}