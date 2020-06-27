<?php

/*
 * AppRequest
 * PS: 请求参数获取、处理
 *
 * @Created: 2020-06-18 15:17:29
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\extend\common;

class AppRequest {

    /**
     * 获取请求参数 支持默认值和过滤
     *
     * @param string $key 获取的变量名
     * @param mixed $default 默认值
     * @param string $filter 过滤方法
     * @param string $type 参数类型 post get
     * @return mixed
     */
    public static function input($key = '', $default = null, $filter = '', $type = 'post') {
        return input("{$type}.{$key}", $default, $filter);
    }

    /**
     * 批量获取请求参数
     *
     * @param array $field 参数
     * @param string $type 参数类型 post get
     * @return array
     */
    public static function inputMany($fields, $type = 'post') {
        // 提取字段类型
        $fieldTypeCollection = [];
        $fieldCollection = [];
        foreach ($fields as $field => $fieldDefault) {
            $fieldInfo = explode('/', $field);
            $field = $fieldInfo[0] ?? '';
            $fieldType = $fieldInfo[1] ?? '';

            if (!$field) {continue;}

            $fieldCollection[$field] = $fieldDefault;
            $fieldTypeCollection[$field] = $fieldType;
        }

        // 获取字段值
        $fieldCollection = request()->only($fieldCollection, $type);

        // 转换类型
        foreach ($fieldCollection as $field => &$value) {
            if (isset($fieldTypeCollection[$field])) {
                $value = self::valueTypeCast($value, $fieldTypeCollection[$field]);
            }
        }
        return $fieldCollection;
    }

    /**
     * 强制类型转换
     *
     * @param string $data 值
     * @param string $type 类型
     * @return value
     */
    protected static function valueTypeCast($data, $type) {
        switch (strtolower($type)) {
        // 数组
        case 'a':
            $data = (array) $data;
            break;
        // 数字
        case 'd':
            $data = (int) $data;
            break;
        // 浮点
        case 'f':
            $data = (float) $data;
            break;
        // 布尔
        case 'b':
            $data = (boolean) $data;
            break;
        // 字符串
        case 's':
            if (is_scalar($data)) {
                $data = (string) $data;
            } else {
                throw new \InvalidArgumentException('variable type error：' . gettype($data));
            }
            break;
        }

        return $data;
    }

}