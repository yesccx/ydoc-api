<?php

/*
 * 文档库分享相关 Service
 *
 * @Created: 2020-07-06 14:45:03
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\service\library;

use app\kernel\model\YLibraryShareModel;

class LibraryShareService {

    /**
     * 根据分享码获取文档库分享信息
     *
     * @param string $shareCode 分享码
     * @param string $field 查询字段
     * @return YLibraryShareModel|null
     */
    public static function getLibraryShareByCode($shareCode, $field = '*') {
        return YLibraryShareModel::findOne(['share_code' => $shareCode], $field);
    }

    /**
     * 获取分享信息
     *
     * @param int $shareId 分享id
     * @param Query|string|null $query 查询器
     * @return mixed
     */
    public static function getLibraryShareInfo($shareId, $query = null) {
        $query = YLibraryShareModel::useQuery($query)->where(['id' => $shareId]);
        return $query->find();
    }

}