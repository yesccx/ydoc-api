<?php

/*
 * 文档修改 Logic
 *
 * @Created: 2020-06-27 14:05:18
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\libraryDoc;

use app\entity\model\YLibraryDocEntity;
use app\exception\AppException;
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
        $this->libraryDocEntity->update_time = time();

        $libraryDoc = YLibraryDocModel::field('title,content,group_id,update_time')->update(
            $this->libraryDocEntity->toArray(),
            ['id' => $this->libraryDocEntity->id]
        );
        if (empty($libraryDoc)) {
            throw new AppException('未知错误');
        }

        return $this;
    }

}