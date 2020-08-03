<?php

/*
 * 文档库用户分享相关 Controller
 *
 * @Created: 2020-07-04 22:19:44
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\controller\v1\library;

use app\entity\model\YLibraryShareEntity;
use app\kernel\base\AppBaseController;
use app\logic\libraryShare\LibraryDocMemberShareCancelLogic;
use app\logic\libraryShare\LibraryDocMemberShareLogic;
use app\logic\libraryShare\LibraryMemberShareCancelLogic;
use app\logic\libraryShare\LibraryMemberShareLogic;
use app\service\library\LibraryMemberShareService;
use think\Db;

class LibraryMemberShareController extends AppBaseController {

    protected $middleware = [
        \app\kernel\middleware\library\LibraryDocAuthMiddleware::class => [ // 文档操作鉴权
            'only' => [
                'docShareInfo', 'docShare', 'docShareCancel',
            ],
        ],
        \app\kernel\middleware\library\LibraryAuthMiddleware::class    => [ // 文档库操作鉴权
            'only' => [
                'libraryShareInfo', 'libraryShare', 'libraryShareCancel',
            ],
        ],
    ];

    /**
     * 文档库文档分享信息
     */
    public function docShareInfo() {
        $libraryDocId = $this->request->libraryDocId;

        $shareInfo = LibraryMemberShareService::getLibraryDocShareInfo(
            $this->uid, $libraryDocId,
            'id,library_id,share_code,share_name,share_desc,access_password,access_count,is_protected,expire_time,create_time,update_time'
        );
        if (empty($shareInfo)) {
            return $this->responseError('分享不存在');
        }

        return $this->responseData($shareInfo);
    }

    /**
     * 文档库文档分享
     */
    public function docShare() {
        $docShareEntity = YLibraryShareEntity::inputMake([
            'share_code/s'      => '',
            'share_name/s'      => '',
            'share_desc/s'      => '',
            'access_password/s' => '',
            'expire_time/d'     => 0,
        ]);
        $docShareEntity->library_id = $this->request->libraryId;
        $docShareEntity->doc_id = $this->request->libraryDocId;
        $docShareEntity->uid = $this->uid;

        $docMemberShare = LibraryDocMemberShareLogic::make();
        Db::transaction(function () use ($docMemberShare, $docShareEntity) {
            $docMemberShare->useDocShare($docShareEntity)->share();
        });

        return $this->responseData($docMemberShare->docShareEntity->toArray());
    }

    /**
     * 文档库文档取消分享
     */
    public function docShareCancel() {
        $docShareEntity = YLibraryShareEntity::make();
        $docShareEntity->library_id = $this->request->libraryId;
        $docShareEntity->doc_id = $this->request->libraryDocId;
        $docShareEntity->uid = $this->uid;

        $docMemberShareCancel = LibraryDocMemberShareCancelLogic::make();
        Db::transaction(function () use ($docMemberShareCancel, $docShareEntity) {
            $docMemberShareCancel->useDocShare($docShareEntity)->shareCancel();
        });

        return $this->responseData('取消成功');
    }

    /**
     * 文档库分享信息
     */
    public function libraryShareInfo() {
        $libraryId = $this->request->libraryId;

        $shareInfo = LibraryMemberShareService::getLibraryShareInfo(
            $this->uid, $libraryId,
            'id,library_id,share_code,share_name,share_desc,access_password,access_count,is_protected,expire_time,create_time,update_time,custom_content'
        );
        if (empty($shareInfo)) {
            return $this->responseError('分享不存在');
        }

        return $this->responseData($shareInfo);
    }

    /**
     * 文档库分享
     */
    public function libraryShare() {
        $docShareEntity = YLibraryShareEntity::inputMake([
            'share_code/s'      => '',
            'share_name/s'      => '',
            'share_desc/s'      => '',
            'access_password/s' => '',
            'custom_content/a'  => [],
            'expire_time/d'     => 0,
        ]);
        $docShareEntity->library_id = $this->request->libraryId;
        $docShareEntity->uid = $this->uid;

        $libraryMemberShare = LibraryMemberShareLogic::make();
        Db::transaction(function () use ($libraryMemberShare, $docShareEntity) {
            $libraryMemberShare->useLibraryShare($docShareEntity)->share();
        });

        return $this->responseData($libraryMemberShare->libraryShareEntity->toArray());
    }

    /**
     * 文档库取消分享
     */
    public function libraryShareCancel() {
        $docShareEntity = YLibraryShareEntity::make();
        $docShareEntity->library_id = $this->request->libraryId;
        $docShareEntity->uid = $this->uid;

        $libraryMemberShareCancel = LibraryMemberShareCancelLogic::make();
        Db::transaction(function () use ($libraryMemberShareCancel, $docShareEntity) {
            $libraryMemberShareCancel->useLibraryShare($docShareEntity)->shareCancel();
        });

        return $this->responseData('取消成功');
    }

}