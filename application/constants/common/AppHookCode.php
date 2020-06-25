<?php

/*
 * AppHook Constant
 *
 * @Created: 2020-06-19 23:27:46
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\constants\common;

use app\constants\extend\BaseCode;

class AppHookCode extends BaseCode {

    // 用户注册成功后
    const USER_REGISTED = 'user_registed';

    // 文档库创建成功后
    const LIBRARY_CREATED = 'library_created';

    // 文档库修改成功后
    const LIBRARY_MODIFYED = 'library_modifyed';

    // 文档库删除成功后
    const LIBRARY_REMOVED = 'library_removed';

    // 文档库转让成功后
    const LIBRARY_TRANSFERED = 'library_transfered';

    // 文档库邀请成员成功后
    const LIBRARY_INVITED = 'library_invited';

    // 文档库成员移除后
    const LIBRARY_MEMBER_UNINVITE = 'library_member_uninvited';

}