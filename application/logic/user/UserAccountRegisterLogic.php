<?php

/*
 * 用户账号注册 Logic
 *
 * @Created: 2020-06-19 16:40:41
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\user;

use app\constants\common\AppHookCode;
use app\entity\model\YUserEntity;
use app\exception\AppException;
use app\extend\common\AppHook;
use app\kernel\model\YUserModel;
use app\kernel\validate\user\UserRegitserValidate;
use app\logic\extend\BaseLogic;
use app\utils\user\UserPasswordHandler;

class UserAccountRegisterLogic extends BaseLogic {

    /**
     * 注册账号
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

        // 校验数据有效性
        UserRegitserValidate::checkOrException($userEntity->toArray());

        $this->userEntity = $userEntity;

        return $this;
    }

    /**
     * 注册
     *
     * @return $this
     * @throws AppException
     */
    public function register() {
        $userEntity = $this->userEntity;

        // 生成随机密码盐
        $userEntity->password_salt = UserPasswordHandler::generateSalt($userEntity->account);

        // 创建用户
        $userInfo = YUserModel::create(
            $userEntity->toArray(),
            'account,password,password_salt,avatar,email,nickname,status,create_time'
        );
        if (empty($userInfo)) {
            throw new AppException('用户信息初始化失败');
        }
        $this->userEntity = $userInfo->toEntity();

        AppHook::listen(AppHookCode::USER_REGISTED, $this->userEntity);

        return $this;
    }

}