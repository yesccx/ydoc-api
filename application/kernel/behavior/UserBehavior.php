<?php

/*
 * 用户相关 behavior
 *
 * @Created: 2020-06-19 23:25:18
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\behavior;

use app\entity\model\YUserEntity;
use app\extend\UserAvatar;
use app\extend\library\LibraryPreferenceHandler;

class UserBehavior {

    /**
     * 用户注册成功后初始化
     *
     * @param YUserEntity $userEntity 用户实体对象
     * @return void
     */
    public function userRegisted(YUserEntity $userEntity) {
        // TODO: 用户注册成功后，初始化头像等信息

        // 初始化用户头像
        (new UserAvatar($userEntity->id))->initUserAvatar();

        // 初始化用户偏好设置参数
        LibraryPreferenceHandler::initUserConfig($userEntity->id);
    }

}
