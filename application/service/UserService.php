<?php

/*
 * 用户相关 Service
 *
 * @Created: 2020-06-20 12:46:02
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\service;

use app\exception\AppException;
use app\kernel\model\YUserModel;

class UserService {

    /**
     * 获取用户信息
     *
     * @param int $uid 用户uid
     * @param Query|string|null $query 查询器
     * @return YUserModel|null
     */
    public static function getUserInfo($uid, $query = null) {
        $query = YUserModel::useQuery($query)->where(['id' => $uid]);
        $userInfo = $query->find();
        if (empty($userInfo)) {
            throw new AppException('用户信息不存在');
        }

        if (!empty($userInfo['avatar'])) {
            $userInfo->append(['avatar_url'])->hidden(['avatar']);
        }

        return $userInfo;
    }

    /**
     * 获取用户集合
     *
     * @param int $limit 查询数量
     * @param Query|string|null $query 查询器
     * @return mixed
     */
    public static function getUserCollect($limit = 10, $query = null) {
        $limit = min(max($limit, 1), 50);
        $query = YUserModel::useQuery($query);
        $userCollect = $query->limit($limit)->select();

        if (!empty($userCollect)) {
            $userCollect->append(['avatar_url'])->hidden(['avatar']);
        }

        return $userCollect;
    }

}