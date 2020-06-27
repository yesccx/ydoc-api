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
     * 文档库分组实体信息
     *
     * @var YLibraryGroupEntity
     */
    public $libraryGroupEntity;

    /**
     * 使用文档库分组信息
     *
     * @param YLibraryGroupEntity $libraryGroupEntity
     * @return $this
     * @throws AppException
     */
    public function useLibraryGroup(YLibraryGroupEntity $libraryGroupEntity) {
        // 校验数据合法性
        LibraryGroupValidate::checkOrException($libraryGroupEntity->toArray(), 'create');

        // 计算新分组排序位置
        if (!$libraryGroupEntity->hasFields('sort') || $libraryGroupEntity->sort < 0) {
            $libraryGroupEntity->sort = $this->computeGroupSort();
        }

        $this->libraryGroupEntity = $libraryGroupEntity;

        return $this;
    }

    /**
     * 计算分组排序
     * PS: 取至文档库未尾分组排序值 + 步长
     *
     * @param int $step 默认间隔步长
     * @return int sort
     */
    protected function computeGroupSort($step = 10000) {
        $lastGroup = YLibraryGroupModel::findOne(['uid' => $this->uid], 'sort', 'sort desc');
        $lastGroupSort = !empty($lastGroup) ? intval($lastGroup['sort'] + $step) : 0;
        $targetSort = empty($lastGroup) ? $step : $lastGroupSort;

        return $targetSort;
    }

    /**
     * 创建文档库分组
     *
     * @return $this
     * @throws AppException
     */
    public function create() {
        if (empty($group = YLibraryGroupModel::create(
            $this->libraryGroupEntity->toArray(),
            'uid,name,desc,sort,create_time,update_time'
        ))) {
            throw new AppException('文档库分组创建失败');
        }

        $this->libraryGroupEntity = $group->toEntity();

        return $this;
    }

}