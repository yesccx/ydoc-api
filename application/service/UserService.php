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
use app\utils\user\UserPasswordHandler;

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
    public static function getUserCollection($limit = 10, $query = null) {
        $limit = min(max($limit, 1), 50);
        $query = YUserModel::useQuery($query);
        $userCollection = $query->limit($limit)->select();

        if (!empty($userCollection)) {
            $userCollection->append(['avatar_url'])->hidden(['avatar']);
        }

        return $userCollection;
    }

    /**
     * 修改用户昵称
     *
     * @param int $uid 用户ui
     * @param string $nickname 新昵称
     * @return mixed
     * @throws AppException
     */
    public static function modifyUserNickname($uid, $nickname) {
        $updateRes = YUserModel::update(['nickname' => $nickname], ['id' => $uid]);
        if (empty($updateRes)) {
            throw new AppException('修改失败');
        }
        return true;
    }

    /**
     * 验证用户登录密码
     *
     * @param int $uid 用户uid
     * @param string $password 待验证的登录密码
     * @return mixed
     * @throws AppException
     */
    public static function checkUserPassword($uid, $password) {
        // 查询用户
        $user = YUserModel::findOne(['id' => $uid], 'id,password,password_salt,status');
        if (empty($user)) {
            return false;
        }

        return UserPasswordHandler::check($user['password'], $password, $user['password_salt']);
    }

}