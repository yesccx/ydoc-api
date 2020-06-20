<?php

/*
 * 模型Entity关联
 *
 * @Created: 2020-06-20 13:32:08
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\model\extend;

use app\entity\extend\BaseEntity;

trait ModelEntity {

    /**
     * 对应实体类
     *
     * @var BaseEntity
     */
    protected $entityClass = '';

    /**
     * 将模型结果转换成对应的实体对象
     * PS: 未关联实体类时，将自动按表名查找类
     *
     * @return BaseEntity
     */
    public function toEntity() {
        if (empty($this->entityClass)) {
            $clazz = parse_name($this->table, 1) . 'Entity';
            $this->entityClass = "app\\entity\\{$clazz}";
        }
        return $this->entityClass::make($this->toArray());
    }

}