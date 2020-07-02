<?php

/*
 * 文档模板删除 Logic
 *
 * @Created: 2020-06-30 10:43:21
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\libraryDocTemplate;

use app\constants\common\AppHookCode;
use app\entity\model\YLibraryDocTemplateEntity;
use app\exception\AppException;
use app\extend\common\AppHook;
use app\kernel\model\YLibraryDocTemplateModel;
use app\logic\extend\BaseLogic;
use app\service\library\LibraryDocTemplateService;

class LibraryDocTemplateRemoveLogic extends BaseLogic {

    /**
     * 文档实体信息
     *
     * @var YLibraryDocTemplateEntity
     */
    public $libraryDocTemplateEntity;

    /**
     * 使用待删除的文档模板id
     *
     * @param int $libraryDocTemplateId 文档模板id
     * @return $this
     * @throws AppException
     */
    public function useLibraryDocTemplate($libraryDocTemplateId = 0) {
        if (empty($libraryDocTemplateId)) {
            throw new AppException('文档模板不存在');
        }

        $libraryDocInfo = LibraryDocTemplateService::getLibraryDocTemplateInfo($libraryDocTemplateId, 'id');
        if (empty($libraryDocInfo)) {
            throw new AppException('文档模板不存在');
        }

        $this->libraryDocTemplateEntity = $libraryDocInfo->toEntity();

        return $this;
    }

    /**
     * 删除文档模板
     *
     * @return $this
     * @throws AppException
     */
    public function remove() {
        $deleteRes = YLibraryDocTemplateModel::where(['id' => $this->libraryDocTemplateEntity->id])->softDelete();
        if (empty($deleteRes)) {
            throw new AppException('删除失败');
        }

        AppHook::listen(AppHookCode::LIBRARY_DOC_TEMPLATE_REMOVED, $this->libraryDocTemplateEntity->id);

        return $this;
    }

}