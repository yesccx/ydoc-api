<?php

/*
 * 用户文档分享取消 Logic
 *
 * @Created: 2020-07-05 18:43:19
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\libraryShare;

use app\entity\model\YLibraryShareEntity;
use app\exception\AppException;
use app\kernel\model\YLibraryShareModel;
use app\logic\extend\BaseLogic;

class LibraryDocMemberShareCancelLogic extends BaseLogic {

    /**
     * 文档分享实体信息
     *
     * @var YLibraryShareEntity
     */
    public $docShareEntity;

    /**
     * 使用文档分享
     *
     * @param YLibraryShareEntity $docShareEntity
     * @return $this
     * @throws AppException
     */
    public function useDocShare($docShareEntity) {
        $docShare = YLibraryShareModel::where([
            'uid'        => $docShareEntity->uid,
            'library_id' => $docShareEntity->library_id,
            'doc_id'     => $docShareEntity->doc_id,
        ])->field('id')->find();
        if (empty($docShare)) {
            throw new AppException('分享不存在');
        }

        $docShareEntity->id = $docShare['id'];
        $this->docShareEntity = $docShareEntity;

        return $this;
    }

    /**
     * 文档分享取消
     *
     * @return $this
     * @throws AppException
     */
    public function shareCancel() {
        YLibraryShareModel::where(['id' => $this->docShareEntity->id])->softDelete();

        return $this;
    }

}