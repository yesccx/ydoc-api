<?php

/*
 * 用户文档库分享取消 Logic
 *
 * @Created: 2020-07-05 23:07:06
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\libraryShare;

use app\entity\model\YLibraryShareEntity;
use app\exception\AppException;
use app\kernel\model\YLibraryShareModel;
use app\logic\extend\BaseLogic;

class LibraryMemberShareCancelLogic extends BaseLogic {

    /**
     * 文档库分享实体信息
     *
     * @var YLibraryShareEntity
     */
    public $libraryShareEntity;

    /**
     * 使用文档库分享
     *
     * @param YLibraryShareEntity $libraryShareEntity
     * @return $this
     * @throws AppException
     */
    public function useLibraryShare($libraryShareEntity) {
        $libraryShare = YLibraryShareModel::where([
            'uid'        => $libraryShareEntity->uid,
            'library_id' => $libraryShareEntity->library_id,
            'doc_id'     => 0,
        ])->field('id')->find();
        if (empty($libraryShare)) {
            throw new AppException('分享不存在');
        }

        $libraryShareEntity->id = $libraryShare['id'];
        $this->libraryShareEntity = $libraryShareEntity;

        return $this;
    }

    /**
     * 文档库分享取消
     *
     * @return $this
     * @throws AppException
     */
    public function shareCancel() {
        YLibraryShareModel::where(['id' => $this->libraryShareEntity->id])->softDelete();

        return $this;
    }

}