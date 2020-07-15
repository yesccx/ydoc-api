<?php

/*
 * 用户密码修改 Logic
 *
 * @Created: 2020-07-15 16:45:15
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\user;

use app\exception\AppException;
use app\kernel\model\YUserModel;
use app\kernel\validate\user\UserValidate;
use app\logic\extend\BaseLogic;
use app\service\UserService;
use app\utils\user\UserPasswordHandler;

class UserPasswordModifyLogic extends BaseLogic {

    /**
     * 新密码
     *
     * @var string
     */
    protected $password = '';

    /**
     * 使用密码
     *
     * @param string $oldPassword 老密码
     * @param string $newPassword 新密码
     * @return $this
     * @throws AppException
     */
    public function usePassword($oldPassword, $newPassword) {
        // 验证老密码
        if ($oldPassword == $newPassword) {
            throw new AppException('新老密码不能一致');
        }
        if (!UserService::checkUserPassword($this->uid, $oldPassword)) {
            throw new AppException('原密码错误');
        }

        // 验证新密码格式
        UserValidate::checkOrException(['password' => $newPassword], 'modifyPassword');

        $this->password = $newPassword;

        return $this;
    }

    /**
     * 提交修改密码
     *
     * @return bool
     * @throws AppException
     */
    public function modify() {
        // 重新生成密码盐
        $passwordSalt = UserPasswordHandler::generateSalt($this->uid);

        $res = YUserModel::update(['password' => $this->password, 'password_salt' => $passwordSalt], ['id' => $this->uid]);
        if (empty($res)) {
            throw new AppException('未知错误');
        }

        return true;
    }

}