<?php

/*
 * 用户账号登录 Logic
 *
 * @Created: 2020-06-18 22:19:12
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\user;

use app\entity\YUserEntity;
use app\exception\AppException;
use app\extend\common\AppQuery;
use app\extend\session\AppSession;
use app\extend\session\AppSessionHandler;
use app\kernel\model\YUserModel;
use app\logic\extend\BaseLogic;
use app\utils\user\UserPasswordHandler;

class UserAccountLoginLogic extends BaseLogic {

    /**
     * 用户实体对象
     *
     * @var YUserEntity
     */
    protected $userEntity;

    /**
     * 待登录的用户信息
     *
     * @var YUserModel
     */
    protected $user;

    /**
     * 使用账号
     *
     * @param YUserEntity $userEntity 用户实体
     * @return $this
     * @throws AppException
     */
    public function useAccount(YUserEntity $userEntity) {
        if (!$userEntity->hasFields(['account', 'password'])) {
            throw new AppException('账号或密码不能为空');
        }

        $this->userEntity = $userEntity;

        return $this;
    }

    /**
     * 登录
     *
     * @return $this
     * @throws AppException
     */
    public function login() {
        // 查询用户
        $user = YUserModel::findOne(AppQuery::make(['account|email' => $this->userEntity->account], 'id,password,password_salt'));
        if (empty($user)) {
            throw new AppException('账号不存在');
        }

        // TODO: 用户登录时，需要判断用户账户是否被禁用

        // 验证账号密码
        if (!($checkPassword = UserPasswordHandler::check($user['password'], $this->userEntity->password, $user['password_salt']))) {
            throw new AppException('用户名或密码错误');
        }

        // TODO: 用户登录时，需要登录记录

        $this->user = $user;

        return $this;
    }

    /**
     * 初始化会话
     *
     * @return AppSession
     * @throws AppException
     */
    public function initSession() {
        $uid = $this->user->id;
        $token = AppSessionHandler::make()->buildToken($uid);
        if (empty($token)) {
            throw new AppException('用户信息初始化失败');
        }

        return AppSession::make()->setSession($uid, $token);
    }

}