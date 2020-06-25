<?php

/*
 * 用户账号登录 Logic
 *
 * @Created: 2020-06-18 22:19:12
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\user;

use app\constants\model\YUserCode;
use app\entity\model\YUserEntity;
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
     * 使用账号信息
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
        $user = YUserModel::findOne(AppQuery::make([
            'account|email' => $this->userEntity->account,
        ], 'id,password,password_salt,status'));

        // 判断用户状态
        if (empty($user)) {
            throw new AppException('账号不存在');
        } else if ($user['status'] === YUserCode::STATUS__DISABLED) {
            throw new AppException('账号已被禁用');
        } else if ($user['status'] === YUserCode::STATUS__AUTHING) {
            throw new AppException('账号正在审核中，请稍后重试');
        }

        // 验证账号密码
        if (!($checkPassword = UserPasswordHandler::check($user['password'], $this->userEntity->password, $user['password_salt']))) {
            throw new AppException('用户名或密码错误');
        }

        // TODO: 用户登录时，需要登录记录

        $this->userEntity = $user->toEntity();

        return $this;
    }

    /**
     * 初始化会话
     *
     * @return AppSession
     * @throws AppException
     */
    public function initSession() {
        $uid = $this->userEntity->id;
        $token = AppSessionHandler::make()->buildToken($uid);
        if (empty($token)) {
            throw new AppException('用户信息初始化失败');
        }

        return AppSession::make()->setSession($uid, $token);
    }

}