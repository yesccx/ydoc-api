<?php

namespace app\service;

use app\extend\common\AppPagination;
use app\kernel\model\YLibraryMemberModel;
use app\kernel\model\YLibraryModel;
use think\db\Query;

class LibraryService {

    /**
     * 获取用户文档库列表
     *
     * @param int $uid 用户uid
     * @param Query|string|null $query 查询器
     * @param AppPagination|null $pagination 分页对象
     * @return AppPagination|null
     */
    public static function getMemberLibraryList($uid, $query = null, $pagination = null) {
        $query = YLibraryMemberModel::useQuery($query)->where(['uid' => $uid]);
        return $query->pageSelect($pagination);
    }

    /**
     * 获取用户所有文档库集合
     *
     * @param int $uid 用户uid
     * @param Query|string|null $query 查询器
     * @return mixed
     */
    public static function getMemberLibraryCollect($uid, $query = null) {
        $query = YLibraryMemberModel::useQuery($query)->where(['uid' => $uid]);
        return $query->select();
    }

    /**
     * 获取文档库信息
     *
     * @param int $libraryId 文档库id
     * @param Query|string|null $query 查询器
     * @return YLibraryModel|null
     */
    public static function getLibraryInfo($libraryId, $query = null) {
        $query = YLibraryModel::useQuery($query)->where(['id' => $libraryId]);
        return $query->find();
    }

    /**
     * 判断文档库成员是否存在
     *
     * @param int $libraryId 文档库id
     * @param int $memberId 成员id
     * @return bool
     */
    public static function existsLibraryMember($libraryId, $memberId) {
        return YLibraryMemberModel::existsOne(['library_id' => $libraryId, 'uid' => $memberId]);
    }

    /**
     * 获取文档库成员信息
     *
     * @param int $libraryId 文档库id
     * @param int $memberId 成员id
     * @return YLibraryMemberModel|null
     */
    public static function getLibraryMemberInfo($libraryId, $memberId, $query = null) {
        $query = YLibraryMemberModel::useQuery($query)->where(['library_id' => $libraryId, 'uid' => $memberId]);
        return $query->find();
    }

}