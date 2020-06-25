<?php

/*
 * 文档库用户操作 Code
 *
 * @Created: 2020-06-24 22:52:13
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\constants\module;

use app\constants\extend\BaseCode;

class LibraryMemberOperateCode extends BaseCode {

    // 文档库修改
    const LIBRARY__MODIFY = 'library__modify';

    // 文档库成员邀请
    const LIBRARY_MEMBER__INVITE = 'library_member__invite';

    // 文档库成员状态修改
    const LIBRARY_MEMBER__STATUS_MODIFY = 'library_member__status_modify';

    // 文档库成员角色修改
    const LIBRARY_MEMBER__ROLE_MODIFY = 'library_member__role_modify';

    // 文档库成员移除
    const LIBRARY_MEMBER__REMOVE = 'library_member__remove';

    // 文档库转让
    const LIBRARY__TRANSFER = 'library__transfer';

    // 文档库归档
    const LIBRARY__PERMANENT = 'library__permanent';

    // 文档库删除
    const LIBRARY__REMOVE = 'library__remove';

}