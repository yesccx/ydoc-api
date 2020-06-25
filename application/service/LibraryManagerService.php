<?php

/*
 * 文档库管理相关 Service
 *
 * @Created: 2020-06-23 15:37:42
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\service;

use app\kernel\model\YLibraryMemberModel;

class LibraryManagerService {

    /**
     * 修改文档库成员的文档库所属分组
     *
     * @param int $libraryId 文档库id
     * @param int $memberId 用户uid
     * @param int $groupId 文档库分组id
     * @param int $sort 成员文档库排序值
     * @return YLibraryMemberModel|null
     */
    public static function modifyLibraryMemberGroup($libraryId, $memberId, $groupId = 0, $sort = -1) {
        $data = ['group_id' => $groupId, 'update_time' => time()];
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
    public static function getLibraryMemberCollect($libraryId, $query = null) {
        $query = YLibraryMemberModel::useQuery($query)->where(['library_id' => $libraryId]);
        $memberCollect = $query->select();

        // 追加用户信息
        if (!empty($memberCollect)) {
            $memberCollect = $memberCollect->load(['user_info' => function ($squery) {
                $squery->field('id,nickname,avatar');
            }])->append(['user_info.avatar_url'])->toArray();
        }

        return $memberCollect;
    }

}