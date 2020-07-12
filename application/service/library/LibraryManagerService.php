<?php

/*
 * 文档库管理相关 Service
 *
 * @Created: 2020-06-23 15:37:42
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\service\library;

use app\kernel\model\YLibraryMemberModel;
use app\kernel\model\YLibraryShareModel;

class LibraryManagerService {

    /**
     * 修改文档库成员的文档库所属分组
     *
     * @param int $libraryId 文档库id
     * @param int $memberId 用户uid
     * @param int $libraryGroupId 文档库分组id
     * @param int $sort 成员文档库排序值
     * @return YLibraryMemberModel|null
     */
    public static function modifyLibraryMemberGroup($libraryId, $memberId, $libraryGroupId = 0, $sort = -1) {
        $data = ['group_id' => $libraryGroupId, 'update_time' => time()];
        if ($sort >= 0) {
            $data['sort'] = $sort;
        }
        return YLibraryMemberModel::update($data, ['library_id' => $libraryId, 'uid' => $memberId]);
    }

    /**
     * 获取文档库成员集合
     *
     * @param int $libraryId 文档库id
     * @param Query|string|null $query 查询器
     * @return mixed
     */
    public static function getLibraryMemberCollection($libraryId, $query = null) {
        $query = YLibraryMemberModel::useQuery($query)->where(['library_id' => $libraryId]);
        $memberCollection = $query->select();

        // 追加用户信息
        if (!empty($memberCollection)) {
            $memberCollection->load(['user_info' => function ($squery) {
                $squery->field('id,nickname,avatar');
            }])->append(['user_info.avatar_url'])->toArray();
        }

        return $memberCollection;
    }

    /**
     * 获取文档库分享列表
     *
     * @param int $libraryId 文档库id
     * @param Query|string|null $query 查询器
     * @param AppPagination|null $pagination 分页对象
     * @return mixed
     */
    public static function getLibraryShareList($libraryId, $query = null, $pagination = null) {
        $query = YLibraryShareModel::useQuery($query)->where(['library_id' => $libraryId]);
        $pageList = $query->pageSelect($pagination)->toArray();

        // 追加用户信息
        if (!empty($pageList['list'])) {
            $pageList['list']->load(['user_info' => function ($squery) {
                $squery->field('id,nickname,avatar');
            }])->append(['user_info.avatar_url'])->toArray();
        }
        return $pageList;
    }

}