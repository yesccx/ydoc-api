<?php

/*
 * 文档库相关 Service
 *
 * @Created: 2020-06-20 14:35:22
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\service\library;

use app\constants\module\LibraryPreferenceCode;
use app\extend\common\AppPagination;
use app\extend\library\LibraryPreferenceHandler;
use app\kernel\model\YLibraryConfigModel;
use app\kernel\model\YLibraryMemberModel;
use app\kernel\model\YLibraryModel;
use app\kernel\model\YUserConfigModel;
use think\db\Query;

class LibraryService {

    /**
     * 获取用户文档库列表
     *
     * @param int $uid 用户uid
     * @param Query|string|null $query 查询器
     * @param AppPagination|null $pagination 分页对象
     * @return AppPagination|null
     */
    public static function getMemberLibraryList($uid, $query = null, $pagination = null) {
        $query = YLibraryMemberModel::useQuery($query)->where(['uid' => $uid]);
        return $query->pageSelect($pagination);
    }

    /**
     * 获取用户所有文档库集合
     *
     * @param int $uid 用户uid
     * @param Query|string|null $query 查询器
     * @return mixed
     */
    public static function getMemberLibraryCollection($uid, $query = null) {
        $query = YLibraryMemberModel::useQuery($query)->where(['uid' => $uid]);
        return $query->select();
    }

    /**
     * 获取用户所有文档库集合
     *
     * @param Query|string|null $query 查询器
     * @return mixed
     */
    public static function getLibraryCollection($query = null) {
        $query = YLibraryModel::useQuery($query);
        return $query->select();
    }

    /**
     * 获取文档库信息
     *
     * @param int $libraryId 文档库id
     * @param Query|string|null $query 查询器
     * @return YLibraryModel|null
     */
    public static function getLibraryInfo($libraryId, $query = null) {
        $query = YLibraryModel::useQuery($query)->where(['id' => $libraryId]);
        return $query->find();
    }

    /**
     * 判断文档库成员是否存在
     *
     * @param int $libraryId 文档库id
     * @param int $memberId 成员id
     * @return bool
     */
    public static function existsLibraryMember($libraryId, $memberId) {
        return YLibraryMemberModel::existsOne(['library_id' => $libraryId, 'uid' => $memberId]);
    }

    /**
     * 获取文档库成员信息
     *
     * @param int $libraryId 文档库id
     * @param int $memberId 成员id
     * @return YLibraryMemberModel|null
     */
    public static function getLibraryMemberInfo($libraryId, $memberId, $query = null) {
        $query = YLibraryMemberModel::useQuery($query)->where(['library_id' => $libraryId, 'uid' => $memberId]);
        return $query->find();
    }

    /**
     * 获取文档库偏好设置
     *
     * @param int $libraryId 文档库id
     * @return array
     */
    public static function getLibraryPreference($libraryId) {
        $libraryConfig = YLibraryConfigModel::where(['uid' => 0, 'library_id' => $libraryId])->field('config')->find();
        if (!empty($libraryConfig) && $libraryConfig['config'][LibraryPreferenceCode::USE_PREFERENCE] == 1) {
            $libraryConfig = $libraryConfig['config'];
        } else {
            $libraryConfig = [];
        }
        return LibraryPreferenceHandler::handle($libraryConfig);
    }

    /**
     * 获取文档库成员偏好设置
     *
     * @param int $libraryId 文档库id
     * @param int $memberId 成员id
     * @return array
     */
    public static function getLibraryMemberPreference($libraryId, $memberId) {
        $resUseConfig = [];

        // 获取文档库偏好设置
        $libraryConfig = YLibraryConfigModel::where(['uid' => 0, 'library_id' => $libraryId])->field('config')->find();
        if (!empty($libraryConfig['config']) && $libraryConfig['config'][LibraryPreferenceCode::USE_PREFERENCE] == 1) {
            $libraryConfig = $libraryConfig['config'];
        } else {
            $libraryConfig = [];
        }

        // 获取成员偏好设置
        $libraryMemberConfig = YLibraryConfigModel::where(['uid' => $memberId, 'library_id' => $libraryId])->field('config')->find();
        if (!empty($libraryMemberConfig['config']) && $libraryMemberConfig['config'][LibraryPreferenceCode::USE_PREFERENCE] == 1) {
            $libraryMemberConfig = $libraryMemberConfig['config'];

            // 成员偏好设置仅支持部分（多余的要删除）
            unset($libraryMemberConfig[LibraryPreferenceCode::LIBRARY_DEFAULT_STYLE]);
        } else {
            $libraryMemberConfig = [];
        }

        // 合并偏好设置
        $resUseConfig = array_merge($libraryConfig, $libraryMemberConfig);
        unset($resUseConfig[LibraryPreferenceCode::LIBRARY_DOC_DEFAULT_EDITOR]);

        // 获取用户偏好设置
        $userConfig = YUserConfigModel::where(['uid' => $memberId])->field('config')->find();
        if (!empty($userConfig['config']) && $userConfig['config'][LibraryPreferenceCode::USE_PREFERENCE] == 1) {
            $userConfig = $userConfig['config'];

            // 当没有有效的偏好设置或成员没有自定义偏好设置时，取用户偏好设置
            if (empty($resUseConfig) || empty($libraryMemberConfig)) {

                // 偏好设置仅支持部分（多余的要删除）
                unset($userConfig[LibraryPreferenceCode::LIBRARY_DEFAULT_STYLE]);

                // 合并偏好设置
                $resUseConfig = array_merge($libraryConfig, $userConfig);
            } else {
                $resUseConfig[LibraryPreferenceCode::LIBRARY_DOC_DEFAULT_EDITOR] = $userConfig[LibraryPreferenceCode::LIBRARY_DOC_DEFAULT_EDITOR];
            }
        }

        return LibraryPreferenceHandler::handle($resUseConfig);
    }

}