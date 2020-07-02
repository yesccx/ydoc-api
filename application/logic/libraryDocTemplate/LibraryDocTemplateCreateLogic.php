<?php

/*
 * 文档模板创建 Logic
 *
 * @Created: 2020-06-30 10:43:21
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\libraryDocTemplate;

use app\constants\common\AppHookCode;
use app\entity\model\YLibraryDocTempalteEntity;
use app\exception\AppException;
use app\extend\common\AppHook;
use app\kernel\model\YLibraryDocTemplateModel;
use app\kernel\validate\library\LibraryDocTemplateValidate;
use app\logic\extend\BaseLogic;

class LibraryDocTemplateCreateLogic extends BaseLogic {

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
        LibraryDocTemplateValidate::checkOrException($libraryDocTemplateEntity->toArray(), 'create');

        $this->libraryDocTemplateEntity = $libraryDocTemplateEntity;

        return $this;
    }

    /**
     * 创建文档模板
     *
     * @return $this
     * @throws AppException
     */
    public function create() {
        $libraryDocTemplate = YLibraryDocTemplateModel::create($this->libraryDocTemplateEntity->toArray(), 'uid,name,introduction,content,create_time');
        if (empty($libraryDocTemplate)) {
            throw new AppException('未知错误');
        }

        $this->libraryDocTemplateEntity = $libraryDocTemplate->toEntity();

        AppHook::listen(AppHookCode::LIBRARY_DOC_TEMPLATE_CREATED, $this->libraryDocTemplateEntity);

        return $this;
    }

}