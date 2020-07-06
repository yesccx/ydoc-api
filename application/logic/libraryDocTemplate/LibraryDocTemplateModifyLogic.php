<?php

/*
 * 文档模板修改 Logic
 *
 * @Created: 2020-06-30 10:43:21
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\libraryDocTemplate;

use app\constants\common\AppHookCode;
use app\exception\AppException;
use app\extend\common\AppHook;
use app\kernel\model\YLibraryDocTemplateModel;
use app\kernel\validate\library\LibraryDocTemplateValidate;
use app\logic\extend\BaseLogic;

class LibraryDocTemplateModifyLogic extends BaseLogic {

    /**
     * 文档模板实体信息
     *
     * @var YLibraryDocTempalteEntity
     */
    public $libraryDocTemplateEntity;

    /**
     * 使用文档模板信息
     *
     * @param YLibraryDocTempalteEntity $libraryDocTemplateEntity
     * @return $this
     * @throws AppException
     */
    public function useLibraryDocTemplate($libraryDocTemplateEntity) {
        LibraryDocTemplateValidate::checkOrException($libraryDocTemplateEntity->toArray(), 'modify');

        $this->libraryDocTemplateEntity = $libraryDocTemplateEntity;

        return $this;
    }

    /**
     * 修改文档模板
     *
     * @return $this
     * @throws AppException
     */
    public function modify() {
        $libraryDocTemplateEntity = $this->libraryDocTemplateEntity;
        $libraryDocTemplateEntity->update_time = time();

        $libraryDocTemplate = YLibraryDocTemplateModel::update(
            $libraryDocTemplateEntity->toArray(), ['id' => $libraryDocTemplateEntity->id], 'name,introduction,content,update_time'
        );
        if (empty($libraryDocTemplate)) {
            throw new AppException('未知错误');
        }

        AppHook::listen(AppHookCode::LIBRARY_DOC_TEMPLATE_MODIFYED, $libraryDocTemplateEntity);

        return $this;
    }

}