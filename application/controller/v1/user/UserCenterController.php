<?php

/*
 * 用户中心 Controller
 *
 * @Created: 2020-06-19 15:35:55
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\controller\v1\user;

use app\extend\session\AppSession;
use app\kernel\base\AppBaseController;
use app\kernel\validate\user\UserValidate;
use app\logic\user\UserPasswordModifyLogic;
use app\service\UserService;
use think\Db;

class UserCenterController extends AppBaseController {

    /**
     * 用户信息
     */
    public function userInfo() {
        $user = UserService::getUserInfo($this->uid, 'id,account,nickname,email,create_time as reg_time,avatar,phone');
        if (empty($user)) {
            return $this->responseSessionInvalid('用户信息不存在');
        }
        return $this->responseData($user);
    }

    /**
     * 用户修改昵称
     */
    public function userNicknameModify() {
        $nickname = $this->input('nickname/s', '');
        if (empty($nickname)) {
            return $this->responseError('用户昵称不能为空');
        }

        // 验证昵称合法性并修改
        UserValidate::checkOrException(['nickname' => $nickname], 'modifyNickname');
        UserService::modifyUserNickname($this->uid, $nickname);

        return $this->responseSuccess('修改成功');
    }

    /**
     * 用户修改密码
     */
    public function userPasswordModify() {
        $oldPassword = $this->input('old_password/s', '');
        $newPassword = $this->input('new_password/s', '');
        if (empty($oldPassword) || empty($newPassword)) {
            return $this->responseError('密码不能为空');
        }

        $userPasswordModify = UserPasswordModifyLogic::make();
        Db::transaction(function () use ($userPasswordModify, $oldPassword, $newPassword) {
            $userPasswordModify->usePassword($oldPassword, $newPassword)->modify();
        });

        // 修改密码成功后，原会话自动失效
        AppSession::make()->destroy();

        return $this->responseSuccess('修改成功');
    }

    /**
     * 用户退出登录
     */
    public function userLogout() {
        AppSession::make()->destroy();
        return $this->responseSuccess('退出登录成功');
    }

}