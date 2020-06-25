<?php

/*
 * 文档库相关 behavior
 *
 * @Created: 2020-06-25 15:58:20
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\behavior;

use app\kernel\model\YLibraryDocGroupModel;
use app\kernel\model\YLibraryDocModel;
use app\kernel\model\YLibraryMemberModel;

class LibraryBehavior {

    /**
     * 文档库删除后，清理
     *
     * @param int $libraryId 文档库id
     * @return void
     */
    public function libraryRemoved($libraryId) {
        YLibraryMemberModel::where(['library_id' => $libraryId])->softDelete();
        YLibraryDocModel::where(['library_id' => $libraryId])->softDelete();
        YLibraryDocGroupModel::where(['library_id' => $libraryId])->softDelete();
    }

}
