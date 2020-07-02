<?php

/*
 * 文档模板相关 Service
 *
 * @Created: 2020-06-28 12:44:53
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\service\library;

use app\kernel\model\YLibraryDocTemplateModel;

class LibraryDocTemplateService {

    /**
     * 获取文档模板集合
     *
     * @param int $uid 用户uid
     * @param Query|string|null $query 查询器
     * @return mixed
     */
    public static function getLibraryDocTemplateCollection($uid, $query = null) {
        $query = YLibraryDocTemplateModel::useQuery($query)->where(['uid' => $uid])->order('id desc');
        $collection = $query->select();
        if (!empty($collection)) {
            $collection->load(['user_info' => function ($squery) {
                $squery->field('id,nickname,account,avatar')->append(['avatar_url'])->hidden(['avatar']);
            }]);
        }

        return $collection;
    }

    /**
     * 获取文档模板信息
     *
     * @param int $templateId 模板id
     * @param Query|string|null $query 查询器
     * @return mixed
     */
    public static function getLibraryDocTemplateInfo($templateId, $query = null) {
        $query = YLibraryDocTemplateModel::useQuery($query)->where(['id' => $templateId]);
        return $query->find();
    }

}