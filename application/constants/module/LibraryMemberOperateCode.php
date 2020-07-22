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

    // 文档库偏好设置修改
    const LIBRARY_CONFIG__MODIFY = 'library_config__modify';

    // 文档库归档
    const LIBRARY__PERMANENT = 'library__permanent';

    // 文档库删除
    const LIBRARY__REMOVE = 'library__remove';

    // 文档分组创建
    const LIBRARY_DOC_GROUP__CREATE = 'library_doc_group__create';

    // 文档分组修改
    const LIBRARY_DOC_GROUP__MODIFY = 'library_doc_group__modify';

    // 文档分组删除
    const LIBRARY_DOC_GROUP__REMOVE = 'library_doc_group__remove';

    // 文档分组排序
    const LIBRARY_DOC_GROUP__SORT = 'library_doc_group__sort';

    // 文档创建
    const LIBRARY_DOC__CREATE = 'library_doc__create';

    // 文档修改
    const LIBRARY_DOC__MODIFY = 'library_doc__modify';

    // 文档删除
    const LIBRARY_DOC__REMOVE = 'library_doc__remove';

    // 文档排序
    const LIBRARY_DOC__SORT = 'library_doc__sort';

    // 文档库分享删除
    const LIBRARY_SHARE__REMOVE = 'library_share__remove';

    // 文档库分享状态修改
    const LIBRARY_SHARE__STATUS_MODIFY = 'library_share__status_modify';

}