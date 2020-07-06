<?php

/*
 * 用户文档分享 Logic
 *
 * @Created: 2020-07-05 12:27:36
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\libraryShare;

use app\entity\model\YLibraryShareEntity;
use app\exception\AppException;
use app\kernel\model\YLibraryShareModel;
use app\kernel\validate\library\LibraryShareValidate;
use app\logic\extend\BaseLogic;
use app\service\library\LibraryMemberShareService;

class LibraryDocMemberShareLogic extends BaseLogic {

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
        // 不存在自定义分享码时，随机生成
        if (empty($docShareEntity->share_code)) {
            $docShareEntity->share_code = $this->generateShareCode();
        }

        // 区分创建和更新分享
        $lastDocShare = LibraryMemberShareService::getLibraryDocShareInfo($docShareEntity->uid, $docShareEntity->doc_id, 'id,share_code');
        if (empty($lastDocShare)) {
            LibraryShareValidate::checkOrException($docShareEntity->toArray(), 'doc-share-create');
        } else {
            $docShareEntity->id = $lastDocShare->id;
            LibraryShareValidate::checkOrException($docShareEntity->toArray(), 'doc-share-update');
        }

        $this->docShareEntity = $docShareEntity;

        return $this;
    }

    /**
     * 构建随机唯一分享码
     *
     * @return string share_code
     */
    protected function generateShareCode() {
        return md5($this->uid . milliTimestamp() . randomStr(10));
    }

    /**
     * 文档分享
     *
     * @return $this
     * @throws AppException
     */
    public function share() {
        if ($this->docShareEntity->hasFields('id')) {
            $this->updateShare();
        } else {
            $this->createShare();
        }

        return $this;
    }

    /**
     * 首次创建分享
     *
     * @return void
     * @throws AppException
     */
    protected function createShare() {
        $docShareEntity = $this->docShareEntity;

        $docShare = YLibraryShareModel::create(
            $docShareEntity->toArray(),
            'doc_id,share_code,share_name,share_desc,access_password,expire_time,update_time,uid,library_id,is_protected'
        );
        if (empty($docShare)) {
            throw new AppException('未知错误');
        }

        $this->docShareEntity = $docShare->toEntity();
    }

    /**
     * 更新分享
     *
     * @return void
     * @throws AppException
     */
    protected function updateShare() {
        $docShareEntity = $this->docShareEntity;
        $docShareEntity->update_time = time();

        $updateRes = YLibraryShareModel::update(
            $docShareEntity->toArray(), ['id' => $docShareEntity->id],
            'share_code,share_name,share_desc,access_password,expire_time,update_time,is_protected'
        );
        if (empty($updateRes)) {
            throw new AppException('未知错误');
        }
    }

}