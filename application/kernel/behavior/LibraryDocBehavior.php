<?php

namespace app\kernel\behavior;

use app\entity\model\YLibraryDocHistoryEntity;
use app\kernel\model\YLibraryDocHistoryModel;
use app\service\library\LibraryDocService;

class LibraryDocBehavior {

    /**
     * 文档修改
     *
     * @param YLibraryDocHistoryEntity $docHistoryEntity
     * @return void
     */
    public function libraryDocModify(YLibraryDocHistoryEntity $docHistoryEntity) {
        $docInfo = LibraryDocService::getLibraryDocInfo($docHistoryEntity->doc_id, 'library_id,group_id,title,content,sort,editor');
        if (empty($docInfo)) {
            return false;
        }

        $docHistoryEntity->library_id = $docInfo['library_id'];
        $docHistoryEntity->group_id = $docInfo['group_id'];
        $docHistoryEntity->title = $docInfo['title'];
        $docHistoryEntity->content = $docInfo['content'];
        $docHistoryEntity->editor = $docInfo['editor'];
        $docHistoryEntity->sort = $docInfo['sort'];

        // 文档修改记录
        YLibraryDocHistoryModel::create($docHistoryEntity->toArray());
    }

}
