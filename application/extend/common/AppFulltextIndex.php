<?php

/*
 * 对数据库全文索引操作类
 *
 * @Created: 2020-07-27 22:39:13
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\extend\common;

use ShaoZeMing\Xunsearch\XunsearchService;

class AppFulltextIndex {

    /**
     * 默认操作的数据集
     *
     * @var string
     */
    public static $defaultDatabase = '';

    /**
     * 实例化对象
     *
     * @param string $database 操作的数据集（参考FulltextIndexCode）
     * @return XunsearchService
     */
    public static function make($database = '') {
        return (new XunsearchService)->setDatabase($database ?: static::$defaultDatabase);
    }

    /**
     * 处理结果数据
     *
     * @param array $data
     * @return arary
     */
    public static function handleResultData($data) {
        $resData = [];
        foreach ($data as $doc) {
            $resData[] = $doc->getFields();
        }
        return $resData;
    }

}