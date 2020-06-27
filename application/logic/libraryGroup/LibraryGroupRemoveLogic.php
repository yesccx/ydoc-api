<?php

/*
 * 文档库分组删除 Logic
 *
 * @Created: 2020-06-22 12:11:56
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\libraryGroup;

use app\exception\AppException;
use app\kernel\model\YLibraryGroupModel;
use app\kernel\validate\library\LibraryGroupValidate;
use app\logic\extend\BaseLogic;

class LibraryGroupRemoveLogic extends BaseLogic {

    /**
     * 文档库分组id
     *
     * @var int
     */
    public $libraryGroupId;

    /**
     * 使用待删除的分组id
     *
     * @param int $libraryGroupId 文档库分组id
     * @return $this
     * @throws AppException
     */
    public function useLibraryGroup($libraryGroupId = 0) {
        LibraryGroupValidate::checkOrException(['id' => $libraryGroupId], 'remove');

        $this->libraryGroupId = $libraryGroupId;

        return $this;
    }

    /**
     * 删除文档库分组
     *
     * @return $this
     * @throws AppException
     */
    public function remove() {
        $deleteRes = YLibraryGroupModel::where(['id' => $this->libraryGroupId])->softDelete();
        if (empty($deleteRes)) {
            throw new AppException('删除失败');
        }

        return $this;
    }

}