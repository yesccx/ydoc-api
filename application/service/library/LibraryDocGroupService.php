<?php

/*
 * 文档分组相关 Service
 *
 * @Created: 2020-06-25 23:05:24
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\service\library;

use app\kernel\model\YLibraryDocGroupModel;

class LibraryDocGroupService {

    /**
     * 获取文档分组集合
     *
     * @param int $libraryId 文档库id
     * @param Query|string|null $query 查询器
     * @return mixed
     */
    public static function getLibraryDocGroupCollection($libraryId, $query = null) {
        $query = YLibraryDocGroupModel::useQuery($query)->where(['library_id' => $libraryId]);
        return $query->select();
    }


    /**
     * 获取文档分组信息
     *
     * @param int $libraryDocGroupId 文档分组id
     * @param Query|string|null $query 查询器
     * @return mixed
     */
    public static function getLibraryDocGroupInfo($libraryDocGroupId, $query = null) {
        $query = YLibraryDocGroupModel::useQuery($query)->where(['id' => $libraryDocGroupId]);
        return $query->find();
    }

}