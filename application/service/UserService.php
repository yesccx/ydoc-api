<?php

/*
 * 用户相关 Service
 *
 * @Created: 2020-06-20 12:46:02
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\service;

use app\extend\common\AppQuery;
use app\kernel\model\YUserModel;

class UserService {

    /**
     * 获取用户信息
     *
     * @param int $uid 用户uid
     * @param string $field 查询字段
     * @return YUserModel|null
     */
    public static function getUserInfo($uid, $field = '') {
        $query = AppQuery::make(['id' => $uid], $field);
        return YUserModel::findOne($query);
    }

}