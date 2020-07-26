<?php

/*
 * 文档相关 Controller
 *
 * @Created: 2020-06-20 14:20:30
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\controller\v1\library;

use app\constants\module\LibraryMemberOperateCode;
use app\entity\model\YLibraryDocEntity;
use app\extend\library\LibraryMemberOperate;
use app\kernel\base\AppBaseController;
use app\logic\libraryDoc\LibraryDocCreateLogic;
use app\logic\libraryDoc\LibraryDocModifyLogic;
use app\logic\libraryDoc\LibraryDocRemoveLogic;
use app\logic\libraryDoc\LibraryDocSortLogic;
use app\service\library\LibraryDocService;
use think\Db;

class LibraryDocController extends AppBaseController {

    protected $middleware = [
        \app\kernel\middleware\library\LibraryAuthMiddleware::class    => [ // 文档库操作鉴权
            'only' => [
                'libraryDocCollection', 'libraryDocInfo', 'libraryDocCreate', 'libraryDocModify',
            ],
        ],
        \app\kernel\middleware\library\LibraryDocAuthMiddleware::class => [ // 文档操作鉴权
            'only' => [
                'libraryDocModify', 'libraryDocInfo', 'libraryDocRemove', 'libraryDocSort',
                'libraryDocBaseModify',
            ],
        ],
    ];

    /**
     * 文档创建
     */
    public function libraryDocCreate() {
        LibraryMemberOperate::checkOperate(LibraryMemberOperateCode::LIBRARY_DOC__CREATE);

        $docEntity = YLibraryDocEntity::inputMake(['title/s' => '', 'content/s' => '', 'group_id/d' => 0, 'editor/s' => '']);
        $docEntity->library_id = $this->request->libraryId;

        $docCreate = LibraryDocCreateLogic::make();
        Db::transaction(function () use ($docCreate, $docEntity) {
            $docCreate->useLibraryDoc($docEntity)->create();
        });

        return $this->responseData($docCreate->libraryDocEntity->toArray());
    }

    /**
     * 文档修改
     */
    public function libraryDocModify() {
        LibraryMemberOperate::checkOperate(LibraryMemberOperateCode::LIBRARY_DOC__MODIFY);

        $docEntity = YLibraryDocEntity::inputMake(['title/s' => '', 'content/s' => '', 'group_id/d' => 0, 'editor/s' => '']);
        $docEntity->library_id = $this->request->libraryId;
        $docEntity->id = $this->request->libraryDocId;

        $docModify = LibraryDocModifyLogic::make();
        Db::transaction(function () use ($docModify, $docEntity) {
            $docModify->useLibraryDoc($docEntity)->modify();
        });

        return $this->responseData($docModify->libraryDocEntity->toArray());
    }

    /**
     * 文档基础信息修改
     */
    public function libraryDocBaseModify() {
        LibraryMemberOperate::checkOperate(LibraryMemberOperateCode::LIBRARY_DOC__MODIFY);

        $docEntity = YLibraryDocEntity::inputMake(['title/s' => '', 'group_id/d' => 0, 'editor/s' => '']);
        $docEntity->library_id = $this->request->libraryId;
        $docEntity->id = $this->request->libraryDocId;

        $docModify = LibraryDocModifyLogic::make();
        Db::transaction(function () use ($docModify, $docEntity) {
            $docModify->useLibraryDoc($docEntity)->modify();
        });

        return $this->responseData($docModify->libraryDocEntity->toArray());
    }

    /**
     * 文档集合
     */
    public function libraryDocCollection() {
        $libraryId = $this->request->libraryId;

        $collection = LibraryDocService::getLibraryDocCollection($libraryId, 'id,library_id,group_id,title,sort,update_time,editor');

        return $this->responseData($collection);
    }

    /**
     * 文档信息
     */
    public function libraryDocInfo() {
        $libraryDocId = $this->request->libraryDocId;

        $docInfo = LibraryDocService::getLibraryDocInfo($libraryDocId, 'id,library_id,group_id,title,content,sort,update_time,editor');

        return $this->responseData($docInfo);
    }

    /**
     * 文档删除
     */
    public function libraryDocRemove() {
        LibraryMemberOperate::checkOperate(LibraryMemberOperateCode::LIBRARY_DOC__REMOVE);

        $libraryDocId = $this->request->libraryDocId;

        $docRemove = LibraryDocRemoveLogic::make();
        Db::transaction(function () use ($docRemove, $libraryDocId) {
            $docRemove->useLibraryDoc($libraryDocId)->remove();
        });

        return $this->responseSuccess('删除成功');
    }

    /**
     * 文档排序
     */
    public function libraryDocSort() {
        LibraryMemberOperate::checkOperate(LibraryMemberOperateCode::LIBRARY_DOC__MODIFY);

        $libraryId = $this->request->libraryId;
        $libraryDocId = $this->request->libraryDocId;
        $parentGroupId = $this->input('parent_group_id/d', 0);
        $sort = $this->input('sort/d', -1);

        $docSort = LibraryDocSortLogic::make();
        Db::transaction(function () use ($docSort, $libraryId, $libraryDocId, $parentGroupId, $sort) {
            $docSort->useLibraryDoc($libraryId, $libraryDocId)->useOption($sort, $parentGroupId)->sort();
        });

        return $this->responseSuccess('修改成功');

    }

}