<?php

/*
 * 文档库分组修改 Logic
 *
 * @Created: 2020-06-22 12:29:58
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\libraryGroup;

use app\entity\model\YLibraryGroupEntity;
use app\exception\AppException;
use app\kernel\model\YLibraryGroupModel;
use app\kernel\validate\library\LibraryGroupValidate;
use app\logic\extend\BaseLogic;

class LibraryGroupModifyLogic extends BaseLogic {

    /**
     * 文档库分组实体信息
     *
     * @var YLibraryGroupEntity
     */
    public $groupEntity;

    /**
     * 使用文档库分组信息
     *
     * @param YLibraryGroupEntity $groupEntity
     * @return $this
     * @throws AppException
     */
    public function useGroup(YLibraryGroupEntity $groupEntity) {
        // 校验数据合法性
        LibraryGroupValidate::checkOrException($groupEntity->toArray(), 'modify');

        $this->groupEntity = $groupEntity;

        return $this;
    }

    /**
     * 修改文档库
     *
     * @return $this
     * @throws AppException
     */
    public function modify() {
        $this->groupEntity->update_time = time();
        $updateRes = YLibraryGroupModel::field('name,desc,sort,update_time')->update($this->groupEntity->toArray(), [
            'id' => $this->groupEntity->id,
        ]);
        if (empty($updateRes)) {
            throw new AppException('修改失败');
        }

        return $this;
    }

}