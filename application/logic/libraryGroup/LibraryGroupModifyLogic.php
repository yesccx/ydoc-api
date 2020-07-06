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
        LibraryGroupValidate::checkOrException($libraryGroupEntity->toArray(), 'modify');

        $this->libraryGroupEntity = $libraryGroupEntity;

        return $this;
    }

    /**
     * 修改文档库分组
     *
     * @return $this
     * @throws AppException
     */
    public function modify() {
        $libraryGroupEntity = $this->libraryGroupEntity;
        $libraryGroupEntity->update_time = time();

        $updateRes = YLibraryGroupModel::update($libraryGroupEntity->toArray(), ['id' => $libraryGroupEntity->id], 'name,desc,sort,update_time');
        if (empty($updateRes)) {
            throw new AppException('修改失败');
        }

        return $this;
    }

}