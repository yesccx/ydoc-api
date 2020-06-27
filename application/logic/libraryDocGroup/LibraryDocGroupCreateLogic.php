<?php

/*
 * 文档分组创建 Logic
 *
 * @Created: 2020-06-25 22:31:51
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\libraryDocGroup;

use app\entity\model\YLibraryDocGroupEntity;
use app\exception\AppException;
use app\extend\common\AppQuery;
use app\kernel\model\YLibraryDocGroupModel;
use app\kernel\model\YLibraryDocModel;
use app\kernel\validate\library\LibraryDocGroupValidate;
use app\logic\extend\BaseLogic;

class LibraryDocGroupCreateLogic extends BaseLogic {

    /**
     * 文档分组实体信息
     *
     * @var YLibraryDocGroupEntity
     */
    public $libraryDocGroupEntity;

    /**
     * 使用文档分组信息
     *
     * @param YLibraryDocGroupEntity $libraryDocGroupEntity
     * @return $this
     * @throws AppException
     */
    public function useLibraryDocGroup(YLibraryDocGroupEntity $libraryDocGroupEntity) {
        // 校验数据合法性
        LibraryDocGroupValidate::checkOrException($libraryDocGroupEntity->toArray(), 'create');

        $this->libraryDocGroupEntity = $libraryDocGroupEntity;

        // 计算新分组排序位置
        if (!$libraryDocGroupEntity->hasFields('sort') || $libraryDocGroupEntity->sort < 0) {
            $this->libraryDocGroupEntity->sort = $this->computeGroupSort();
        }

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
        // 获取末尾分组排序值
        $lastGroup = YLibraryDocGroupModel::findOne(AppQuery::make(
            ['library_id' => $this->libraryDocGroupEntity->library_id, 'pid' => $this->libraryDocGroupEntity->pid],
            'sort', 'sort desc'
        ));
        $lastGroupSort = $lastGroup ? intval($lastGroup['sort'] + $step) : 0;

        // 获取末尾文档排序值
        $lastDoc = YLibraryDocModel::findOne(AppQuery::make(
            ['library_id' => $this->libraryDocGroupEntity->library_id, 'group_id' => $this->libraryDocGroupEntity->pid],
            'sort', 'sort desc'
        ));
        $lastDocSort = $lastDoc ? intval($lastDoc['sort'] + $step) : 0;

        return (empty($lastGroup) && empty($lastDoc)) ? $step : max($lastGroupSort, $lastDocSort);
    }

    /**
     * 创建文档分组
     *
     * @return $this
     * @throws AppException
     */
    public function create() {
        if (empty($group = YLibraryDocGroupModel::create(
            $this->libraryDocGroupEntity->toArray(),
            'name,desc,library_id,pid,sort,create_time,update_time'
        ))) {
            throw new AppException('文档分组创建失败');
        }

        $this->libraryDocGroupEntity = $group->toEntity();

        return $this;
    }

}