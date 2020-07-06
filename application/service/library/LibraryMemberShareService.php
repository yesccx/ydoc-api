<?php

/*
 * 文档库分享相关 Service
 *
 * @Created: 2020-07-04 22:23:13
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\service\library;

use app\kernel\model\YLibraryShareModel;

class LibraryMemberShareService {

    /**
     * 获取用户的文档库文档分享信息
     *
     * @param int $uid 用户uid
     * @param int $docId 文档id
     * @param Query|string|null $query 查询器
     * @return YLibraryModel|null
     */
    public static function getLibraryDocShareInfo($uid, $docId, $query = null) {
        $query = YLibraryShareModel::useQuery($query)->where(['uid' => $uid, 'doc_id' => $docId]);
        return $query->find();
    }

    /**
     * 获取用户的文档库分享信息
     *
     * @param int $uid 用户uid
     * @param int $libraryId 文档库id
     * @param Query|string|null $query 查询器
     * @return YLibraryModel|null
     */
    public static function getLibraryShareInfo($uid, $libraryId, $query = null) {
        $query = YLibraryShareModel::useQuery($query)->where(['uid' => $uid, 'library_id' => $libraryId, 'doc_id' => 0]);
        return $query->find();
    }

}