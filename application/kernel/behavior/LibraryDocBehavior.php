<?php

/*
 * 文档相关操作事件 处理
 *
 * @Created: 2020-07-03 18:33:43
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\behavior;

use app\constants\common\LibraryOperateCode;
use app\entity\model\YLibraryDocEntity;
use app\entity\model\YLibraryDocHistoryEntity;
use app\extend\library\LibraryDocFulltextIndex;
use app\extend\library\LibraryOperateLog;
use app\kernel\model\YLibraryDocHistoryModel;
use app\service\library\LibraryDocService;

class LibraryDocBehavior {

    /**
     * 文档创建后
     * LIBRARY_DOC_CREATE_AFTER
     *
     * @param array @params
     * @return void
     */
    public function libraryDocCreateAfter($params) {
        $docEntity = $params[0];
        $uid = $params[1] ?? 0;

        $docInfo = LibraryDocService::getLibraryDocInfo($docEntity->id, 'id,library_id,group_id,title,content,sort,editor');
        if (empty($docInfo)) {
            return false;
        }
        $docEntity = YLibraryDocEntity::make($docInfo);

        // 新建文档全文索引
        LibraryDocFulltextIndex::addIndex($docEntity);

        // 文档库操作日志
        LibraryOperateLog::make($docEntity->library_id, $uid)->write(LibraryOperateCode::LIBRARY_DOC_CREATE, '文档：' . $docEntity->title, $docEntity->toArray());
    }

    /**
     * 文档删除前
     * LIBRARY_DOC_REMOVE_BEFORE
     *
     * @param array @params
     * @return void
     */
    public function libraryDocRemoveBefore($params) {
        $docId = $params[0];
        $uid = $params[1] ?? 0;

        $docInfo = LibraryDocService::getLibraryDocInfo($docId, 'id,library_id,title');
        if (empty($docInfo)) {
            return false;
        }

        // 删除文档对应的全文索引
        LibraryDocFulltextIndex::delIndex($docId);

        // 文档库操作日志
        LibraryOperateLog::make($docInfo['library_id'], $uid)->write(LibraryOperateCode::LIBRARY_DOC_REMOVE, '文档：' . $docInfo['title'], $docInfo);
    }

    /**
     * 文档修改前
     * LIBRARY_DOC_MODIFY_BEFORE
     *
     * @param array @params
     * @return void
     */
    public function libraryDocModifyBefore($params) {
        $docEntity = $params[0];
        $uid = $params[1] ?? 0;

        $docInfo = LibraryDocService::getLibraryDocInfo($docEntity->id, 'id,library_id,group_id,title,content,sort,editor');
        if (empty($docInfo)) {
            return false;
        }
        $docEntity = YLibraryDocEntity::make($docInfo);

        // 追加文档修改记录
        $this->appendLibraryDocHistory($docEntity, $uid);
    }

    /**
     * 文档修改后
     * LIBRARY_DOC_MODIFY_AFTER
     *
     * @param array @params
     * @return void
     * @return void
     */
    public function libraryDocModifyAfter($params) {
        $docEntity = $params[0];
        $uid = $params[1] ?? 0;

        $docInfo = LibraryDocService::getLibraryDocInfo($docEntity->id, 'id,library_id,group_id,title,content,sort,editor');
        if (empty($docInfo)) {
            return false;
        }
        $docEntity = YLibraryDocEntity::make($docInfo);

        // 更新文档全文索引
        LibraryDocFulltextIndex::updateIndexOne($docEntity);

        // 文档库操作日志
        LibraryOperateLog::make($docEntity->library_id, $uid)->write(LibraryOperateCode::LIBRARY_DOC_MODIFY, '文档：' . $docEntity->title, $docEntity->toArray());
    }

    /**
     * 追加文档修改记录
     *
     * @param YLibraryDocEntity $docEntity
     * @param int $uid 操作人uid
     * @return void
     */
    protected function appendLibraryDocHistory(YLibraryDocEntity $docEntity, $uid) {
        $docHistoryEntity = YLibraryDocHistoryEntity::make([
            'library_id' => $docEntity->library_id,
            'uid'        => $uid,
            'doc_id'     => $docEntity->id,
            'group_id'   => $docEntity->group_id,
            'title'      => $docEntity->title,
            'content'    => $docEntity->content,
            'editor'     => $docEntity->editor,
            'sort'       => $docEntity->sort,
        ]);

        YLibraryDocHistoryModel::create($docHistoryEntity->toArray());
    }

}
