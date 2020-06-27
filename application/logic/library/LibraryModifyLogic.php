<?php

/*
 * 文档库修改 Logic
 *
 * @Created: 2020-06-22 18:14:08
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\library;

use app\constants\common\AppHookCode;
use app\entity\model\YLibraryEntity;
use app\exception\AppException;
use app\extend\common\AppHook;
use app\kernel\model\YLibraryModel;
use app\kernel\validate\library\LibraryValidate;
use app\logic\extend\BaseLogic;

class LibraryModifyLogic extends BaseLogic {

    /**
     * 文档库实体信息
     *
     * @var YLibraryEntity
     */
    public $libraryEntity;

    /**
     * 使用文档库信息
     *
     * @param YLibraryEntity $libraryEntity
     * @return $this
     * @throws AppException
     */
    public function useLibrary(YLibraryEntity $libraryEntity) {
        // 校验数据合法性
        LibraryValidate::checkOrException($libraryEntity->toArray(), 'modify');

        $this->libraryEntity = $libraryEntity;

        return $this;
    }

    /**
     * 修改文档库
     *
     * @return $this
     * @throws AppException
     */
    public function modify() {
        $this->libraryEntity->update_time = time();

        $libraryInfo = YLibraryModel::field('name,desc,update_time')->update(
            $this->libraryEntity->toArray(), ['id' => $this->libraryEntity->id]
        );
        if (empty($libraryInfo)) {
            throw new AppException('文档库修改失败，可能操作过快');
        }

        AppHook::listen(AppHookCode::LIBRARY_MODIFYED, $this->libraryEntity);

        return $this;
    }

}