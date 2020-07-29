<?php

/*
 * 文档库相关 behavior
 *
 * @Created: 2020-06-25 15:58:20
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\behavior;

use app\extend\library\LibraryDocFulltextIndex;
use app\kernel\model\YLibraryConfigModel;
use app\kernel\model\YLibraryDocGroupModel;
use app\kernel\model\YLibraryDocHistoryModel;
use app\kernel\model\YLibraryDocModel;
use app\kernel\model\YLibraryMemberModel;
use app\kernel\model\YLibraryShareModel;

class LibraryBehavior {

    /**
     * 文档库删除后
     * PS: 清理文档库相关内容
     *
     * @param int $libraryId 文档库id
     * @return void
     */
    public function libraryRemoveAfter($libraryId) {
        YLibraryMemberModel::where(['library_id' => $libraryId])->softDelete();
        YLibraryDocModel::where(['library_id' => $libraryId])->softDelete();
        YLibraryDocGroupModel::where(['library_id' => $libraryId])->softDelete();
        YLibraryDocHistoryModel::where(['library_id' => $libraryId])->softDelete();
        YLibraryConfigModel::where(['library_id' => $libraryId])->softDelete();
        YLibraryShareModel::where(['library_id' => $libraryId])->softDelete();

        // 删除文档库全文索引
        LibraryDocFulltextIndex::delIndexByLibrary($libraryId);
    }

}
