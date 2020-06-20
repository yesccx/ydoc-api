<?php

/*
 * 用户相关 Service
 *
 * @Created: 2020-06-20 12:46:02
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\service;

use app\exception\AppException;
use app\extend\common\AppQuery;
use app\kernel\model\YUserModel;

class UserService {

    /**
     * 获取用户信息
     *
     * @param int $uid 用户uid
     * @param string $field 查询字段
     * @return YUserModel|null
     * @throws AppException
     */
    public static function getUserInfo($uid, $field = '') {
        $query = AppQuery::make(['id' => $uid], $field);
        $userInfo = YUserModel::findOne($query);
        if (empty($userInfo)) {
            throw new AppException('用户信息不存在');
        }

        if (!empty($userInfo['avatar'])) {
            $userInfo->append(['avatar_url'])->hidden(['avatar']);
        }

        return $userInfo;
    }

}