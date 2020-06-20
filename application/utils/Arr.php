<?php

/*
 * 数组相关工具类
 *
 * @Created: 2020-06-19 17:35:14
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\utils;

use ArrayAccess;

class Arr {

    /**
     * 确定给定值是否是数组
     *
     * @param mixed $value
     * @return boolean
     */
    public static function accessible($value) {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    /**
     * 确定给定的值是否存在于数组中
     *
     * @param array|\ArrayAccess $array
     * @param int|string $key
     * @return boolean
     */
    public static function exists($array, $key) {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }
        return array_key_exists($key, $array);
    }
}
