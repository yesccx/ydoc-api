<?php

/*
 * 文档分组修改 Logic
 *
 * @Created: 2020-06-26 10:35:09
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\libraryDocGroup;

use app\constants\common\LibraryOperateCode;
use app\entity\model\YLibraryDocGroupEntity;
use app\exception\AppException;
use app\extend\library\LibraryOperateLog;
use app\kernel\model\YLibraryDocGroupModel;
use app\kernel\validate\library\LibraryDocGroupValidate;
use app\logic\extend\BaseLogic;

class LibraryDocGroupModifyLogic extends BaseLogic {

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
        LibraryDocGroupValidate::checkOrException($libraryDocGroupEntity->toArray(), 'modify');

        // TODO: 文档库分组变更上级分组时，需要重新计算分组sort值

        $this->libraryDocGroupEntity = $libraryDocGroupEntity;

        return $this;
    }

    /**
     * 修改分组
     *
     * @return $this
     * @throws AppException
     */
    public function modify() {
        $libraryDocGroupEntity = $this->libraryDocGroupEntity;
        $libraryDocGroupEntity->update_time = time();

        $updateRes = YLibraryDocGroupModel::update(
            $libraryDocGroupEntity->toArray(), ['id' => $libraryDocGroupEntity->id], 'pid,name,desc,sort,update_time'
        );
        if (empty($updateRes)) {
            throw new AppException('修改失败');
        }

        // 文档库操作日志
        LibraryOperateLog::record(
            $libraryDocGroupEntity->library_id, LibraryOperateCode::LIBRARY_DOC_GROUP_MODIFY, '文档分组：' . $libraryDocGroupEntity->name, $libraryDocGroupEntity->toArray()
        );

        return $this;
    }

}