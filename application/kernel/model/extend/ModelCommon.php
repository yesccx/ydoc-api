<?php

/*
 * Model扩展静态方法
 *
 * @Created: 2020-06-19 08:15:12
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\model\extend;

use app\extend\common\AppQuery;
use think\db\Query;
use think\Model;

trait ModelCommon {

    /**
     * 使用Query(AppQuery)
     *
     * @param Query|array|string $query
     * @return Model
     */
    public static function useQuery($query) {
        /** @var Model */
        $instance = new static;

        if (is_array($query)) {
            $query = AppQuery::make($query);
        } else if (is_string($query)) {
            $query = AppQuery::make([], $query);
        } else if (!($query instanceof Query)) {
            $query = AppQuery::make();
        }

        return $instance->setOption('where', $query->getOptions('where'))
            ->setOption('field', $query->getOptions('field'))
            ->setOption('order', $query->getOptions('order'))
            ->setOption('group', $query->getOptions('group'))
            ->setOption('limit', $query->getOptions('limit'));
    }

    /**
     * 单个查询
     *
     * @param Query|array $query
     * @param mixed $field 字段
     * @param mixed $order 排序
     * @return Model|null
     */
    public static function findOne($query, $field = true, $order = '') {
        if (!($query instanceof Query)) {
            $query = AppQuery::make($query, $field, $order);
        }
        return static::useQuery($query)->find();
    }

    /**
     * 查询数量
     *
     * @param Query|array $query
     * @return Model|null
     */
    public static function findCount($query = []) {
        return static::useQuery($query)->count();
    }

    /**
     * 判断是否存在
     *
     * @param Query|array $query
     * @return Model|null
     */
    public static function existsOne($query = []) {
        return static::useQuery($query)->count() > 0;
    }

}
