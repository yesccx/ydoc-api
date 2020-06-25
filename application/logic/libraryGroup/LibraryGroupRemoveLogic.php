<?php

/*
 * 文档库分组删除 Logic
 *
 * @Created: 2020-06-22 12:11:56
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\libraryGroup;

use app\entity\model\YLibraryGroupEntity;
use app\exception\AppException;
use app\extend\common\AppQuery;
use app\kernel\model\YLibraryGroupModel;
use app\logic\extend\BaseLogic;
use app\service\LibraryGroupService;

class LibraryGroupRemoveLogic extends BaseLogic {

    /**
     * 文档库分组实体信息
     *
     * @var YLibraryGroupEntity
     */
    public $libraryGroupEntity;

    /**
     * 使用待删除的分组id
     *
     * @param int $groupId 文档库分组id
     * @return $this
     * @throws AppException
     */
    public function useLibraryGroup($groupId = 0) {
        if (empty($groupId)) {
            throw new AppException('文档库分组不存在');
        }

        $libraryGroupInfo = LibraryGroupService::getLibraryGroupInfo($groupId, AppQuery::make(['uid' => $this->uid], 'id,uid'));
        if (empty($libraryGroupInfo)) {
            throw new AppException('文档库分组不存在');
        }

        $this->libraryGroupEntity = $libraryGroupInfo->toEntity();

        return $this;
    }

    /**
     * 删除分组
     *
     * @return $this
     * @throws AppException
     */
    public function remove() {
        $deleteRes = YLibraryGroupModel::where(['id' => $this->libraryGroupEntity->id])->softDelete();
        if (empty($deleteRes)) {
            throw new AppException('删除失败');
        }

        return $this;
    }

}