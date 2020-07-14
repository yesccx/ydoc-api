<?php

/*
 * 文档分组删除 Logic
 *
 * @Created: 2020-06-26 10:49:41
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\libraryDocGroup;

use app\constants\common\LibraryOperateCode;
use app\entity\model\YLibraryDocGroupEntity;
use app\exception\AppException;
use app\extend\library\LibraryOperateLog;
use app\kernel\model\YLibraryDocGroupModel;
use app\kernel\model\YLibraryDocModel;
use app\logic\extend\BaseLogic;
use app\service\library\LibraryDocGroupService;

class LibraryDocGroupRemoveLogic extends BaseLogic {

    /**
     * 文档分组实体信息
     *
     * @var YLibraryDocGroupEntity
     */
    public $libraryDocGroupEntity;

    /**
     * 删除的文档id集
     *
     * @var array
     */
    public $childDocIdCollection = [];

    /**
     * 是否深度删除
     * PS: 递归删除当前分组下的分组、文档
     *
     * @var bool
     */
    protected $isDeep = false;

    /**
     * 使用待删除的文档分组id
     *
     * @param int $groupId 文档分组id
     * @return $this
     * @throws AppException
     */
    public function useLibraryDocGroup($groupId = 0) {
        if (empty($groupId)) {
            throw new AppException('文档分组不存在');
        }

        $libraryGroupInfo = LibraryDocGroupService::getLibraryDocGroupInfo($groupId, 'id');
        if (empty($libraryGroupInfo)) {
            throw new AppException('文档分组不存在');
        }

        $this->libraryDocGroupEntity = $libraryGroupInfo->toEntity();

        return $this;
    }

    /**
     * 决定是否使用深度删除
     *
     * @param bool $isDeep
     * @return $this
     * @throws AppException
     */
    public function useDeep($isDeep = false) {
        if (!$isDeep) {
            $childrenGroupCount = YLibraryDocGroupModel::findCount(['pid' => $this->libraryDocGroupEntity->id]);
            if ($childrenGroupCount > 0) {
                throw new AppException('请先清空该分组下的分组');
            }
            $childrenDocCount = YLibraryDocModel::findCount(['group_id' => $this->libraryDocGroupEntity->id]);
            if ($childrenDocCount > 0) {
                throw new AppException('请先清空该分组下的文档');
            }
        }

        $this->isDeep = $isDeep;

        return $this;
    }

    /**
     * 删除文档分组
     *
     * @return $this
     * @throws AppException
     */
    public function remove() {
        if ($this->isDeep) {
            $this->deepRemoveChildren($this->libraryDocGroupEntity->id, true);
        }

        $docGroupInfo = LibraryDocGroupService::getLibraryDocGroupInfo($this->libraryDocGroupEntity->id, 'id,library_id,name');

        $deleteRes = YLibraryDocGroupModel::where(['id' => $this->libraryDocGroupEntity->id])->softDelete();
        if (empty($deleteRes)) {
            throw new AppException('删除失败');
        }

        // 文档库操作日志
        LibraryOperateLog::record(
            $docGroupInfo['library_id'], LibraryOperateCode::LIBRARY_DOC_GROUP_REMOVE, '文档分组：' . $docGroupInfo['name'], $docGroupInfo->toArray()
        );

        return $this;
    }

    /**
     * 递归删除子级分组及子级文档
     *
     * @param integer $parentGroupId 上级分组id
     * @param boolean $isRoot 代表当前是否为根级（不需要传）
     * @return array 被删除的分组id、文档id集合
     */
    protected function deepRemoveChildren($parentGroupId, $isRoot = false) {
        // 获取当前分组的子级分组id、文档id集合
        $childGroupIdCollection = YLibraryDocGroupModel::where(['pid' => $parentGroupId])->column('id');
        $childDocIdCollection = YLibraryDocModel::where(['group_id' => $parentGroupId])->column('id');

        // 递归处理子分组的数据
        foreach ($childGroupIdCollection as $groupId) {
            $child = $this->deepRemoveChildren($groupId, false);
            if (!empty($child)) {
                $childDocIdCollection = array_merge($childDocIdCollection, $child['docIdCollection']);
                $childGroupIdCollection = array_merge($childGroupIdCollection, $child['groupIdCollection']);
            }
        }

        // 在递归末尾统一做删除动作
        if ($isRoot) {
            if (!empty($childDocIdCollection)) {
                $this->childDocIdCollection = $childDocIdCollection;
                YLibraryDocModel::whereIn('id', $childDocIdCollection)->softDelete();
            }
            if (!empty($childGroupIdCollection)) {
                YLibraryDocGroupModel::whereIn('id', $childGroupIdCollection)->softDelete();
            }
        }

        return ['docIdCollection' => $childDocIdCollection, 'groupIdCollection' => $childGroupIdCollection];
    }

}