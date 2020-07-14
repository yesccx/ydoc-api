<?php

/*
 * 文档删除 Logic
 *
 * @Created: 2020-06-27 17:33:42
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\libraryDoc;

use app\constants\common\LibraryOperateCode;
use app\entity\model\YLibraryDocEntity;
use app\exception\AppException;
use app\extend\library\LibraryOperateLog;
use app\kernel\model\YLibraryDocModel;
use app\logic\extend\BaseLogic;
use app\service\library\LibraryDocService;

class LibraryDocRemoveLogic extends BaseLogic {

    /**
     * 文档实体信息
     *
     * @var YLibraryDocEntity
     */
    public $libraryDocEntity;

    /**
     * 使用待删除的文档id
     *
     * @param int $libraryDocId 文档id
     * @return $this
     * @throws AppException
     */
    public function useLibraryDoc($libraryDocId = 0) {
        if (empty($libraryDocId)) {
            throw new AppException('文档不存在');
        }

        $libraryDocInfo = LibraryDocService::getLibraryDocInfo($libraryDocId, 'id');
        if (empty($libraryDocInfo)) {
            throw new AppException('文档不存在');
        }

        $this->libraryDocEntity = $libraryDocInfo->toEntity();

        return $this;
    }

    /**
     * 删除文档分组
     *
     * @return $this
     * @throws AppException
     */
    public function remove() {
        $docInfo = LibraryDocService::getLibraryDocInfo($this->libraryDocEntity->id, 'library_id,title');

        $deleteRes = YLibraryDocModel::where(['id' => $this->libraryDocEntity->id])->softDelete();
        if (empty($deleteRes)) {
            throw new AppException('删除失败');
        }

        // 文档库操作日志
        LibraryOperateLog::record(
            $docInfo['library_id'], LibraryOperateCode::LIBRARY_DOC_REMOVE, '文档：' . $docInfo['title'], $docInfo
        );

        return $this;
    }

}