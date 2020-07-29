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

    // 用户注册后
    const USER_REGISTER_AFTER = 'user_register_after';

    // 文档库创建后
    const LIBRARY_CREATE_AFTER = 'library_create_after';

    // 文档库修改后
    const LIBRARY_MODIFY_AFTER = 'library_modify_after';

    // 文档库删除后
    const LIBRARY_REMOVE_AFTER = 'library_remove_after';

    // 文档库转让后
    const LIBRARY_TRANSFER_AFTER = 'library_transfer_after';

    // 文档库邀请成员后
    const LIBRARY_INVITE_AFTER = 'library_invite_after';

    // 文档库成员移除后
    const LIBRARY_MEMBER_UNINVITE_AFTER = 'library_member_uninvite_after';

    // 文档创建后
    const LIBRARY_DOC_CREATE_AFTER = 'library_doc_create_after';

    // 文档修改前
    const LIBRARY_DOC_MODIFY_BEFORE = 'library_doc_modify_before';

    // 文档修改后
    const LIBRARY_DOC_MODIFY_AFTER = 'library_doc_modify_after';

    // 文档删除后
    const LIBRARY_DOC_REMOVE_AFTER = 'library_doc_remove_after';

    // 文档删除前
    const LIBRARY_DOC_REMOVE_BEFORE = 'library_doc_remove_before';

    // 文档模板创建后
    const LIBRARY_DOC_TEMPLATE_CREATE_AFTER = 'library_doc_template_create_after';

    // 文档模板修改后
    const LIBRARY_DOC_TEMPLATE_MODIFY_AFTER = 'library_doc_template_modify_after';

    // 文档模板删除后
    const LIBRARY_DOC_TEMPLATE_REMOVE_AFTER = 'library_doc_template_remove_after';

}