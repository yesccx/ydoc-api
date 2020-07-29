<?php

/*
 * 文档修改 Logic
 *
 * @Created: 2020-06-27 14:05:18
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\libraryDoc;

use app\constants\common\AppHookCode;
use app\constants\common\LibraryOperateCode;
use app\entity\model\YLibraryDocEntity;
use app\entity\model\YLibraryDocHistoryEntity;
use app\exception\AppException;
use app\extend\common\AppHook;
use app\extend\library\LibraryOperateLog;
use app\kernel\model\YLibraryDocModel;
use app\kernel\validate\library\LibraryDocValidate;
use app\logic\extend\BaseLogic;

class LibraryDocModifyLogic extends BaseLogic {

    /**
     * 文档实体信息
     *
     * @var YLibraryDocEntity
     */
    public $libraryDocEntity;

    /**
     * 使用文档信息
     *
     * @param YLibraryDocEntity $libraryDocEntity
     * @return $this
     * @throws AppException
     */
    public function useLibraryDoc($libraryDocEntity) {
        LibraryDocValidate::checkOrException($libraryDocEntity->toArray(), 'modify');

        $this->libraryDocEntity = $libraryDocEntity;

        return $this;
    }

    /**
     * 修改文档
     *
     * @return $this
     * @throws AppException
     */
    public function modify() {
        $libraryDocEntity = $this->libraryDocEntity;
        $libraryDocEntity->update_time = time();

        // 文档修改前，需要触发保存历史记录
        AppHook::listen(AppHookCode::LIBRARY_DOC_MODIFY_BEFORE, [$libraryDocEntity, $this->uid]);

        $libraryDoc = YLibraryDocModel::update(
            $libraryDocEntity->toArray(), ['id' => $libraryDocEntity->id], 'title,content,group_id,update_time,editor'
        );
        if (empty($libraryDoc)) {
            throw new AppException('未知错误');
        }

        AppHook::listen(AppHookCode::LIBRARY_DOC_MODIFY_AFTER, [$libraryDocEntity, $this->uid]);

        return $this;
    }

}