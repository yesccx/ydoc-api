<?php

/*
 * AppQuery
 * PS: 应用于Model层
 *
 * @Created: 2020-06-19 09:03:50
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\extend\common;

use think\db\Query;

class AppQuery {

    /**
     * 初始化
     *
     * @param array|mixed $condition 查询条件
     * @param string $field 查询字段
     * @param string $order 排序
     * @return Query
     */
    public static function make($condition = [], $field = '', $order = '') {
        $query = new Query;

        if ($condition) {
            $query->where($condition);
        }
        if ($field) {
            $query->field($field);
        }
        if ($order) {
            $query->order($order);
        }

        return $query;
    }

}