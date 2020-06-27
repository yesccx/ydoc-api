<?php

/*
 * 文档库成员的文档库排序 Logic
 *
 * @Created: 2020-06-23 15:43:06
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\libraryManager;

use app\entity\model\YLibraryMemberEntity;
use app\exception\AppException;
use app\logic\extend\BaseLogic;
use app\service\library\LibraryGroupService;
use app\service\library\LibraryManagerService;
use app\service\library\LibraryService;

class LibraryMemberLibrarySortLogic extends BaseLogic {

    /**
     * 文档库成员实体信息
     *
     * @var YLibraryMemberEntity
     */
    protected $libraryMemberEntity;

    /**
     * 使用文档库成员
     *
     * @param int $uid 用户uid
     * @param int $libraryId 文档库id
     * @return $this
     * @throws AppException
     */
    public function useLibraryMember($uid, $libraryId) {
        // 准备文档库成员信息
        $libraryMember = LibraryService::getLibraryMemberInfo($libraryId, $uid, 'id,library_id,uid,status,urole,group_id,sort');
        if (empty($libraryMember)) {
            throw new AppException('文档库成员不存在');
        }

        $this->libraryMemberEntity = $libraryMember->toEntity();

        return $this;
    }

    /**
     * 使用文档库分组
     *
     * @param int $libraryGroupId 文档库分组id
     * @param int $sort 文档库在分组下的排序值
     * @return $this
     * @throws AppException
     */
    public function useLibraryGroup($libraryGroupId, $sort = -1) {
        // 存在非空分组id时，判断分组是否存在
        if ($libraryGroupId > 0) {
            $libraryGroup = LibraryGroupService::getLibraryGroupInfo($libraryGroupId, ['uid' => $this->libraryMemberEntity->uid]);
            if (empty($libraryGroup)) {
                throw new AppException('文档库分组不存在');
            }
        }

        $this->libraryMemberEntity->sort = $sort;
        $this->libraryMemberEntity->group_id = $libraryGroupId;

        return $this;
    }

    /**
     * 排序文档库分组
     *
     * @return $this
     * @throws AppException
     */
    public function sort() {
        $modifyRes = LibraryManagerService::modifyLibraryMemberGroup(
            $this->libraryMemberEntity->library_id,
            $this->libraryMemberEntity->uid,
            $this->libraryMemberEntity->group_id,
            $this->libraryMemberEntity->sort
        );
        if (empty($modifyRes)) {
            throw new AppException('更新失败');
        }

        return $this;
    }

}