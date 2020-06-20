<?php

/*
 * Base Entity
 *
 * @Created: 2020-06-19 17:32:50
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\entity\extend;

use app\utils\Arr;

abstract class BaseEntity {

    /**
     * 实体数据
     *
     * @var array
     */
    protected $entityData = [];

    /**
     * 对应模型类
     *
     * @var string
     */
    protected $model = '';

    public function __get($name) {
        return $this->getField($name, null);
    }

    public function __isset($name) {
        return $this->getField($name, null) !== null;
    }

    public function __set($name, $value) {
        $this->setField($name, $value);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray() {
        return $this->getData();
    }

    /**
     * 静态初始化
     *
     * @param array $entityData 实体数据
     * @return static
     */
    public static function make($entityData = []) {
        return (new static )->setData($entityData);
    }

    /**
     * 获取字段
     *
     * @param string $field 字段名
     * @param string $default 缺省值
     * @return mixed
     */
    public function getField($field, $default = '') {
        $fieldCollect = explode('.', is_int($field) ? (string) $field : $field);
        if (empty($fieldCollect)) {
            return $default;
        }

        $entityData = $this->entityData;
        if (!is_null($segment = array_shift($fieldCollect))) {
            if (Arr::accessible($entityData) && Arr::exists($entityData, $segment)) {
                $entityData = $entityData[$segment];
            } else if (is_object($entityData) && isset($entityData->{$segment})) {
                $entityData = $entityData->{$segment};
            } else {
                return $default;
            }
        }
        return $entityData;
    }

    /**
     * 判断是否存在某个字段
     *
     * @param string|array $fields 字段名
     * @return boolean
     */
    public function hasFields($fields) {
        if (is_array($fields)) {
            foreach ($fields as $field) {
                if (!isset($this->entityData[$field])) {
                    return false;
                }
            }
            return true;
        }
        return isset($this->entityData[$fields]);
    }

    /**
     * 设置字段
     * // FIXME: 需要支持二级数组的更改操作
     *
     * @param string $field 字段名
     * @param mixed $value 字段值
     * @return void
     */
    public function setField($field, $value) {
        $this->entityData[$field] = $value;
    }

    /**
     * 设置实体数据
     *
     * @param array $entityData 实体数据
     * @return $this
     */
    public function setData($entityData) {
        $this->entityData = $entityData;
        return $this;
    }

    /**
     * 获取实体数据
     *
     * @return array
     */
    public function getData() {
        return $this->entityData;
    }

}