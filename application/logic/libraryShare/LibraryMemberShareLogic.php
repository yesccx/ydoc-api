<?php

/*
 * 用户文档库分享 Logic
 *
 * @Created: 2020-07-05 23:07:06
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\libraryShare;

use app\entity\model\YLibraryShareEntity;
use app\exception\AppException;
use app\kernel\model\YLibraryShareModel;
use app\kernel\validate\library\LibraryShareValidate;
use app\logic\extend\BaseLogic;
use app\service\library\LibraryMemberShareService;

class LibraryMemberShareLogic extends BaseLogic {

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
        // 不存在自定义分享码时，随机生成
        if (empty($libraryShareEntity->share_code)) {
            $libraryShareEntity->share_code = $this->generateShareCode();
        }

        // 区分创建和更新分享
        $lastLibraryShare = LibraryMemberShareService::getLibraryShareInfo($libraryShareEntity->uid, $libraryShareEntity->library_id, 'id,share_code');
        if (empty($lastLibraryShare)) {
            LibraryShareValidate::checkOrException($libraryShareEntity->toArray(), 'library-share-create');
        } else {
            $libraryShareEntity->id = $lastLibraryShare->id;
            LibraryShareValidate::checkOrException($libraryShareEntity->toArray(), 'library-share-update');
        }

        $this->libraryShareEntity = $libraryShareEntity;

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
        if ($this->libraryShareEntity->hasFields('id')) {
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
        $libraryShareEntity = $this->libraryShareEntity;

        $docShare = YLibraryShareModel::create(
            $libraryShareEntity->toArray(),
            'share_code,share_name,share_desc,access_password,expire_time,update_time,uid,library_id,is_protected'
        );
        if (empty($docShare)) {
            throw new AppException('未知错误');
        }

        $this->libraryShareEntity = $docShare->toEntity();
    }

    /**
     * 更新分享
     *
     * @return void
     * @throws AppException
     */
    protected function updateShare() {
        $libraryShareEntity = $this->libraryShareEntity;
        $libraryShareEntity->update_time = time();

        $updateRes = YLibraryShareModel::update(
            $libraryShareEntity->toArray(), ['id' => $libraryShareEntity->id],
            'share_code,share_name,share_desc,access_password,expire_time,update_time,is_protected'
        );
        if (empty($updateRes)) {
            throw new AppException('未知错误');
        }
    }

}