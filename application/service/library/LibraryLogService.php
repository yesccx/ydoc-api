<?php

/*
 * 文档库日志相关 Service
 *
 * @Created: 2020-07-13 22:24:33
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\service\library;

use app\kernel\model\YLibraryLogModel;

class LibraryLogService {

    /**
     * 获取文档库日志列表
     *
     * @param Query|string|null $query 查询器
     * @param AppPagination|null $pagination 分页对象
     * @return AppPagination|null
     */
    public static function getLibraryLogList($query = null, $pagination = null) {
        $query = YLibraryLogModel::useQuery($query);
        return $query->pageSelect($pagination);
    }

}