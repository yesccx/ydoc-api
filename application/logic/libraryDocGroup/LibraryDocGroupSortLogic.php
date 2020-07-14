<?php

/*
 * 文档分组排序 Logic
 *
 * @Created: 2020-06-27 09:43:31
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\libraryDocGroup;

use app\constants\common\LibraryOperateCode;
use app\entity\model\YLibraryDocGroupEntity;
use app\exception\AppException;
use app\extend\library\LibraryOperateLog;
use app\kernel\model\YLibraryDocGroupModel;
use app\logic\extend\BaseLogic;
use app\service\library\LibraryDocGroupService;

class LibraryDocGroupSortLogic extends BaseLogic {

    /**
     * 文档分组实体信息
     *
     * @var YLibraryDocGroupEntity
     */
    protected $libraryDocGroupEntity;

    /**
     * 使用文档分组
     *
     * @param int $libraryId 文档库id
     * @param int $libraryDocGroupId 文档分组id
     * @return $this
     * @throws AppException
     */
    public function useLibraryDocGroup($libraryId, $libraryDocGroupId) {
        $libraryDocGroupEntity = YLibraryDocGroupEntity::make(['id' => $libraryDocGroupId, 'library_id' => $libraryId]);

        // TODO: 文档分组变更上级分组时，需要重新计算分组sort值

        $this->libraryDocGroupEntity = $libraryDocGroupEntity;

        return $this;
    }

    /**
     * 指定上级分组、排序
     *
     * @param int $sort 分组排序值
     * @param int $parentGroupId 上级文档分组id
     * @return $this
     * @throws AppException
     */
    public function useOption($sort, $parentGroupId = -1) {
        $this->libraryDocGroupEntity->sort = $sort;

        if ($parentGroupId >= 0) {
            if ($parentGroupId == 0) {
                $parentGroupId = 0;
            } else if (!YLibraryDocGroupModel::existsOne(['id' => $parentGroupId, 'library_id' => $this->libraryDocGroupEntity->library_id])) {
                throw new AppException('上级文档分组不存在');
            }
            $this->libraryDocGroupEntity->pid = $parentGroupId;
        }

        return $this;
    }

    /**
     * 排序文档分组
     *
     * @return $this
     * @throws AppException
     */
    public function sort() {
        $libraryDocGroupEntity = $this->libraryDocGroupEntity;
        $libraryDocGroupEntity->update_time = time();

        $updateRes = YLibraryDocGroupModel::update($libraryDocGroupEntity->toArray(), ['id' => $libraryDocGroupEntity->id], 'pid,sort,update_time');
        if (empty($updateRes)) {
            throw new AppException('修改失败，请重试');
        }

        // 文档库操作日志
        $docGroupInfo = LibraryDocGroupService::getLibraryDocGroupInfo($libraryDocGroupEntity->id, 'id,library_id,name');
        LibraryOperateLog::record(
            $docGroupInfo['library_id'], LibraryOperateCode::LIBRARY_DOC_GROUP_MODIFY, '文档分组：' . $docGroupInfo['name'], $docGroupInfo->toArray()
        );

        return $this;
    }

}