<?php

/*
 * 文档相关 Service
 *
 * @Created: 2020-06-25 23:15:53
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\service\library;

use app\kernel\model\YLibraryDocModel;

class LibraryDocService {

    /**
     * 获取文档集合
     *
     * @param int $libraryId 文档库id
     * @param Query|string|null $query 查询器
     * @return mixed
     */
    public static function getLibraryDocCollection($libraryId, $query = null) {
        $query = YLibraryDocModel::useQuery($query)->where(['library_id' => $libraryId]);
        return $query->select();
    }

    /**
     * 获取文档信息
     *
     * @param int $docId 文档id
     * @param Query|string|null $query 查询器
     * @return mixed
     */
    public static function getLibraryDocInfo($docId, $query = null) {
        $query = YLibraryDocModel::useQuery($query)->where(['id' => $docId]);
        return $query->find();
    }

}