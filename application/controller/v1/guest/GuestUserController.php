<?php

/*
 * 游客用户相关 Controller
 *
 * @Created: 2020-06-18 22:12:24
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\controller\v1\guest;

use app\entity\YUserEntity;
use app\kernel\base\AppBaseController;
use app\logic\user\UserAccountLoginLogic;
use app\logic\user\UserAccountRegisterLogic;
use think\Db;

class GuestUserController extends AppBaseController {

    /**
     * 账号登录
     */
    public function accountLogin() {
        $accountInfo = $this->inputMany(['account/s' => '', 'password/s' => '']);
        if (empty($accountInfo['account']) || empty($accountInfo['password'])) {
            return $this->responseError('请输入账号密码！');
        }

        $userEntity = YUserEntity::make($accountInfo);
        $accountLogin = UserAccountLoginLogic::make();
        Db::transaction(function () use ($accountLogin, $userEntity) {
            $accountLogin->useAccount($userEntity)->login();
        });

        return $this->responseData($accountLogin->initSession());
    }

    /**
     * 账号注册
     */
    public function accountRegister() {
        $accountInfo = $this->inputMany(['account/s' => '', 'password/s' => '', 'email/s' => '']);
        if (empty($accountInfo['account']) || empty($accountInfo['password'])) {
            return $this->responseError('请输入账号密码！');
        }

        $userEntity = YUserEntity::make($accountInfo);
        $userRegister = new UserAccountRegisterLogic;

        Db::transaction(function () use ($userRegister, $userEntity) {
            $userRegister->useAccount($userEntity)->register();
        });

        return $this->responseSuccess('注册成功');
    }

}
