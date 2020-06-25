<?php

/*
 * YLibraryMemberModel Code
 *
 * @Created: 2020-06-20 22:20:15
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\constants\model;

use app\constants\extend\BaseCode;

class YLibraryMemberCode extends BaseCode {

    // 成员状态：审核中（初始状态）
    const STATUS__NORMAL = 0;

    // 成员状态：启用
    const STATUS__ENABLED = 1;

    // 成员状态：禁用
    const STATUS__DISABLED = 2;

    // 成员角色：创建人
    const ROLE__CREATOR = 1;

    // 成员角色：管理员
    const ROLE__MANAGER = 2;

    // 成员角色：普通成员
    const ROLE__MEMBER = 3;

    // 成员角色：只读成员
    const ROLE__GUEST = 4;

    // 映射
    public static $map = [
        'status' => [
            self::STATUS__NORMAL   => '审核中',
            self::STATUS__ENABLED   => '启用',
            self::STATUS__DISABLED => '禁用',
        ],
        'urole'  => [
            self::ROLE__CREATOR => '创建人',
            self::ROLE__MANAGER => '管理员',
            self::ROLE__MEMBER  => '普通成员',
            self::ROLE__GUEST   => '只读成员',
        ],
    ];

}