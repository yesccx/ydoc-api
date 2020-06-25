<?php

/*
 * 文档库分组创建 Logic
 *
 * @Created: 2020-06-20 17:26:15
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\libraryGroup;

use app\entity\model\YLibraryGroupEntity;
use app\exception\AppException;
use app\kernel\model\YLibraryGroupModel;
use app\kernel\validate\library\LibraryGroupValidate;
use app\logic\extend\BaseLogic;

class LibraryGroupCreateLogic extends BaseLogic {

    /**
     * 分组实体信息
     *
     * @var YLibraryGroupEntity
     */
    public $groupEntity;

    /**
     * 使用分组信息
     *
     * @param YLibraryGroupEntity $groupEntity
     * @return $this
     * @throws AppException
     */
    public function useGroup(YLibraryGroupEntity $groupEntity) {
        // 校验数据合法性
        LibraryGroupValidate::checkOrException($groupEntity->toArray(), 'create');

        // 计算新分组排序位置
        $groupEntity->sort = $this->computeGroupSort();

        $this->groupEntity = $groupEntity;

        return $this;
    }

    /**
     * 计算分组排序
     * PS: 取至文档库未尾分组排序值 + 10000
     *
     * @return int sort
     */
    protected function computeGroupSort() {
        $lastGroup = YLibraryGroupModel::findOne(['uid' => $this->uid], 'sort', 'sort desc');
        $lastGroupSort = !empty($lastGroup) ? intval($lastGroup['sort'] + 10000) : 0;
        $targetSort = empty($lastGroup) ? 10000 : $lastGroupSort;

        return $targetSort;
    }

    /**
     * 创建分组
     *
     * @return $this
     * @throws AppException
     */
    public function create() {
        if (empty($group = YLibraryGroupModel::create($this->groupEntity->toArray()))) {
            throw new AppException('文档库分组创建失败');
        }

        $this->groupEntity = $group->toEntity();

        return $this;
    }

}