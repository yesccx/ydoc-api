<?php

/*
 * BaseCode
 *
 * @Created: 2020-06-18 14:43:37
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\constants\extend;

abstract class BaseCode {

    public static $map = [];

    /**
     * 当前键类型
     *
     * @var string
     */
    protected $type = '';

    public function __construct($type) {
        $this->type = $type;
    }

    /**
     * 静态初始化
     *
     * @param string $type 键类型名
     * @return self
     */
    public static function make($type) {
        return new static($type);
    }

    /**
     * 判断某个键值是否存在
     *
     * @param string $val 键值
     * @return boolean
     */
    public function has($val) {
        return !empty(static::$map[$this->type][$val]);
    }

    /**
     * 获取键类型的所有键值列表或指定键值
     *
     * @param mixed $key 键名
     * @return array|boolean
     */
    public function get($key = null) {
        $data = static::$map[$this->type] ?? [];
        if ($key !== null) {
            return $data[$key] ?? false;
        }
        return $data;
    }

}