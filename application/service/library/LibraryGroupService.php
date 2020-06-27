<?php

/*
 * 文档库分组相关 Service
 *
 * @Created: 2020-06-20 16:59:45
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\service\library;

use app\kernel\model\YLibraryGroupModel;

class LibraryGroupService {

    /**
     * 获取文档库分组集合
     *
     * @param int $uid 用户uid
     * @param Query|string|null $query 查询器
     * @return mixed
     */
    public static function getLibraryGroupCollection($uid, $query = null) {
        $query = YLibraryGroupModel::useQuery($query)->where(['uid' => $uid])->order('sort asc,id desc');
        return $query->select();
    }

    /**
     * 获取文档库分组信息
     *
     * @param int $libraryGroupId 文档库分组id
     * @param Query|string|null $query 查询器
     * @return YLibraryGroupModel|null
     */
    public static function getLibraryGroupInfo($libraryGroupId, $query = null) {
        $query = YLibraryGroupModel::useQuery($query)->where(['id' => $libraryGroupId]);
        return $query->find();
    }

    /**
     * 判断文档库分组是否存在
     *
     * @param int $libraryGroupId 文档库分组id
     * @param Query|string|null $query 查询器
     * @return bool
     */
    public static function existsLibraryGroup($libraryGroupId, $query = null) {
        return YLibraryGroupModel::useQuery($query)->where(['id' => $libraryGroupId])->count() > 0;
    }

    /**
     * 排序文档库分组
     *
     * @param int $libraryGroupId 文档分组id
     * @param float $sort 排序值
     * @return mixed
     */
    public static function modifyLibraryGroupSort($libraryGroupId, $sort) {
        return YLibraryGroupModel::update(['sort' => $sort], ['id' => $libraryGroupId]);
    }

}