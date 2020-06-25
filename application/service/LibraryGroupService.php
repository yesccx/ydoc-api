<?php

/*
 * 文档库分组相关 Service
 *
 * @Created: 2020-06-20 16:59:45
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\service;

use app\kernel\model\YLibraryGroupModel;

class LibraryGroupService {

    /**
     * 获取文档库分组集合
     *
     * @param int $uid 用户uid
     * @param Query|string|null $query 查询器
     * @return mixed
     */
    public static function getLibraryGroupCollect($uid, $query = null) {
        $query = YLibraryGroupModel::useQuery($query)->where(['uid' => $uid])->order('sort asc,id desc');
        return $query->select();
    }

    /**
     * 获取文档库分组信息
     *
     * @param int $groupId 文档库分组id
     * @param Query|string|null $query 查询器
     * @return YLibraryGroupModel|null
     */
    public static function getLibraryGroupInfo($groupId, $query = null) {
        $query = YLibraryGroupModel::useQuery($query)->where(['id' => $groupId]);
        return $query->find();
    }

    /**
     * 判断文档库分组是否存在
     *
     * @param int $groupId 文档库分组id
     * @param Query|string|null $query 查询器
     * @return bool
     */
    public static function existsLibraryGroup($groupId, $query = null) {
        return YLibraryGroupModel::useQuery($query)->where(['id' => $groupId])->count() > 0;
    }

    /**
     * 排序文档库分组
     *
     * @param integer $groupId 文档分组id
     * @param float $sort 排序值
     * @return mixed
     */
    public static function modifyLibraryGroupSort($groupId, $sort) {
        return YLibraryGroupModel::update(['sort' => $sort], ['id' => $groupId]);
    }

}