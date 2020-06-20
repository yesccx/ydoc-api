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
use app\service\UserService;

class UserCenterController extends AppBaseController {

    /**
     * 用户信息
     */
    public function userInfo() {
        $user = UserService::getUserInfo($this->uid, 'id,nickname,create_time as reg_time,avatar');
        if (empty($user)) {
            return $this->responseSessionInvalid('用户信息不存在');
        }
        return $this->responseData($user);
    }

    /**
     * 用户退出登录
     */
    public function userLogout() {
        AppSession::make()->destroy();
        return $this->responseSuccess('退出登录成功');
    }

}