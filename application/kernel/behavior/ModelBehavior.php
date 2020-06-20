<?php

/*
 * 模型Query扩展
 *
 * @Created: 2020-06-20 09:50:16
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\behavior;

use app\extend\common\AppPagination;
use think\db\Query;

class ModelBehavior {

    public function appBegin() {
        /**
         * 配合AppPagination类做分页select
         *
         * @param Query $query
         * @param AppPagination $paginate
         * @return AppPagination
         */
        Query::extend('pageSelect', function (Query $query, AppPagination $paginate) {
            if ($paginate === null) {
                return $query->select();
            }

            $countQuery = clone $query;

            // 查询列表
            if (!empty($paginate->pageOrderField)) {
                $query->order($paginate->pageOrderField, $paginate->pageOrder > 0 ? 'asc' : 'desc');
            }
            $list = $query->page($paginate->pageNum, $paginate->pageSize)->select();

            // 统计列表
            if ($paginate->pageNum == 1 && count($list) == 0) {
                $total = 0;
            } else {
                $total = $countQuery->count();
            }

            return $paginate->setPageData([
                'list'      => $list,
                'total'     => $total,
                'page_size' => $paginate->pageSize,
                'page_num'  => $paginate->pageNum,
            ]);
        });
    }

}