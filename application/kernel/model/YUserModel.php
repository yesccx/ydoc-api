<?php

/*
 * y_user（用户信息表） Model
 *
 * @Created: 2020-06-18 22:02:07
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\model;

use app\extend\UserAvatar;
use app\kernel\model\extend\BaseModel;
use app\utils\user\UserPasswordHandler;

class YUserModel extends BaseModel {

    protected $pk = 'id';

    protected $table = 'y_user';

    protected $insert = ['create_time', 'password_salt', 'nickname', 'avatar'];

    // 自动完成：创建时间
    protected function setCreateTimeAttr() {
        return time();
    }

    // 自动完成：用户昵称
    protected function setNicknameAttr($nickname, $data) {
        if (empty($nickname)) {
            $nickname = $data['account'] ?? randomStr(16);
        }
        return $nickname;
    }

    // 自动完成：用户头像
    protected function setAvatarAttr($avatar, $data) {
        if (empty($avatar)) {
            $avatar = md5($data['account'] . randomStr(10));
        }

        return $avatar;
    }

    // 自动完成：用户头像url
    protected function getAvatarUrlAttr($avatar, $data) {
        return (new UserAvatar($data['id']))->getUserAvatarUrl();
    }

    // 自动完成：生成密码盐
    protected function setPasswordSaltAttr($salt) {
        if (empty($salt)) {
            $salt = UserPasswordHandler::generateSalt();
        }
        return $salt;
    }

    // 修改器：密码加盐
    protected function setPasswordAttr($password, $data) {
        return UserPasswordHandler::encryption($password, $data['password_salt']);
    }
}