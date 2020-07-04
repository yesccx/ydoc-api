<?php

/*
 * 文档历史相关 Service
 *
 * @Created: 2020-07-03 16:57:49
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\service\library;

use app\extend\common\AppPagination;
use app\kernel\model\YLibraryDocHistoryModel;
use think\db\Query;

class LibraryDocHistoryService {

    /**
     * 获取文档历史记录列表
     *
     * @param int $docId 文档id
     * @param Query|string|null $query 查询器
     * @param AppPagination|null $pagination 分页对象
     * @return AppPagination|null
     */
    public static function getLibraryDocHistoryList($docId, $query = null, $pagination = null) {
        $query = YLibraryDocHistoryModel::useQuery($query)->where(['doc_id' => $docId]);
        return $query->pageSelect($pagination);
    }

    /**
     * 获取文档历史记录信息
     *
     * @param int $docTemplateId 文档库id
     * @param Query|string|null $query 查询器
     * @return YLibraryDocHistoryModel|null
     */
    public static function getLibraryDocHistoryInfo($docTemplateId, $query = null) {
        $query = YLibraryDocHistoryModel::useQuery($query)->where(['id' => $docTemplateId]);
        return $query->find();
    }

}