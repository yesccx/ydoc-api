<?php

/*
 * 文档库操作日志说明集
 *
 * @Created: 2020-07-14 14:54:13
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\extend\library;

use app\constants\common\LibraryOperateCode;

class LibraryOperateLogMessage {

    /**
     * 操作说明映射
     *
     * @var array
     */
    protected static $operateMessageCollection = [
        LibraryOperateCode::LIBRARY_CREATE               => '创建文档库',
        LibraryOperateCode::LIBRARY_MODIFY               => '修改文档库',
        LibraryOperateCode::LIBRARY_REMOVE               => '删除文档库',
        LibraryOperateCode::LIBRARY_INVITE               => '邀请用户加入文档库',
        LibraryOperateCode::LIBRARY_TRANSFER             => '转让文档库',
        LibraryOperateCode::LIBRARY_UNINVITE             => '将用户从文档库移除，来自文档库',
        LibraryOperateCode::LIBRARY_MEMBER_STATUS_MODIFY => '变更文档库成员状态，来自文档库',
        LibraryOperateCode::LIBRARY_DOC_CREATE           => '创建文档，来自文档库',
        LibraryOperateCode::LIBRARY_DOC_MODIFY           => '修改文档，来自文档库',
        LibraryOperateCode::LIBRARY_DOC_REMOVE           => '删除文档，来自文档库',
        LibraryOperateCode::LIBRARY_DOC_GROUP_CREATE     => '创建文档分组，来自文档库',
        LibraryOperateCode::LIBRARY_DOC_GROUP_MODIFY     => '修改文档分组，来自文档库',
        LibraryOperateCode::LIBRARY_DOC_GROUP_REMOVE     => '删除文档分组，来自文档库',
    ];

    /**
     * 解析operate
     *
     * @param string $operate
     * @return string
     */
    public static function parse($operate) {
        return static::$operateMessageCollection[$operate] ?? '';
    }

}