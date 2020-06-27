<?php

/*
 * 文档分组相关 Controller
 *
 * @Created: 2020-06-20 14:20:38
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\controller\v1\library;

use app\constants\module\LibraryMemberOperateCode;
use app\entity\model\YLibraryDocGroupEntity;
use app\extend\library\LibraryDocGroupTree;
use app\extend\library\LibraryMemberOperate;
use app\kernel\base\AppBaseController;
use app\logic\libraryDocGroup\LibraryDocGroupCreateLogic;
use app\logic\libraryDocGroup\LibraryDocGroupModifyLogic;
use app\logic\libraryDocGroup\LibraryDocGroupRemoveLogic;
use app\logic\libraryDocGroup\LibraryDocGroupSortLogic;
use app\service\library\LibraryDocGroupService;
use think\Db;

class LibraryDocGroupController extends AppBaseController {

    protected $middleware = [
        \app\kernel\middleware\library\LibraryAuthMiddleware::class         => [ // 文档库操作鉴权
            'only' => [
                'groupCreate', 'groupTree',
            ],
        ],
        \app\kernel\middleware\library\LibraryDocGroupAuthMiddleware::class => [ // 文档分组操作鉴权
            'only' => [
                'groupInfo', 'groupModify', 'groupRemove', 'groupSort',
            ],
        ],
    ];

    /**
     * 文档分组树
     */
    public function groupTree() {
        $libraryId = $this->request->libraryId;

        // 获取文档分组集合，构建分组树
        $collection = LibraryDocGroupService::getLibraryDocGroupCollection($libraryId, 'id,uid,library_id,pid,name,desc,sort');
        $tree = LibraryDocGroupTree::buildTree(0, $collection);

        return $this->responseData($tree);
    }

    /**
     * 文档分组创建
     */
    public function groupCreate() {
        LibraryMemberOperate::checkOperate(LibraryMemberOperateCode::LIBRARY_DOC_GROUP__CREATE);

        $libraryId = $this->request->libraryId;
        $groupInfo = $this->inputMany(['name/s' => '', 'desc/s' => '', 'sort/d' => 0, 'pid/d' => 0]);

        // 准备文档库分组实体信息
        $groupEntity = YLibraryDocGroupEntity::make($groupInfo);
        $groupEntity->library_id = $libraryId;

        $docGroupCreate = LibraryDocGroupCreateLogic::make();
        Db::transaction(function () use ($docGroupCreate, $groupEntity) {
            $docGroupCreate->useLibraryDocGroup($groupEntity)->create();
        });

        return $this->responseData($docGroupCreate->libraryDocGroupEntity->toArray());
    }

    /**
     * 文档分组修改
     */
    public function groupModify() {
        LibraryMemberOperate::checkOperate(LibraryMemberOperateCode::LIBRARY_DOC_GROUP__MODIFY);

        $docGroupId = $this->request->libraryDocGroupId;
        $groupInfo = $this->inputMany(['name/s' => '', 'desc/s' => '', 'sort/d' => 0, 'pid/d' => 0]);

        // 准备文档库分组实体信息
        $groupEntity = YLibraryDocGroupEntity::make($groupInfo);
        $groupEntity->id = $docGroupId;

        $docGroupModify = LibraryDocGroupModifyLogic::make();
        Db::transaction(function () use ($docGroupModify, $groupEntity) {
            $docGroupModify->useLibraryDocGroup($groupEntity)->modify();
        });

        return $this->responseData($docGroupModify->libraryDocGroupEntity->toArray());
    }

    /**
     * 文档分组删除
     */
    public function groupRemove() {
        LibraryMemberOperate::checkOperate(LibraryMemberOperateCode::LIBRARY_DOC_GROUP__REMOVE);

        $docGroupId = $this->request->libraryDocGroupId;
        $isDeep = $this->input('is_deep/d', 0);

        // 深度删除时，需要同时使用文档删除的权限
        if ($isDeep) {
            LibraryMemberOperate::checkOperate(LibraryMemberOperateCode::LIBRARY_DOC__REMOVE);
        }

        $docGroupRemove = LibraryDocGroupRemoveLogic::make();
        Db::transaction(function () use ($docGroupRemove, $docGroupId, $isDeep) {
            $docGroupRemove->useLibraryDocGroup($docGroupId)->useDeep($isDeep)->remove();
        });

        return $this->responseSuccess('删除成功');
    }

    /**
     * 文档分组排序
     */
    public function groupSort() {
        LibraryMemberOperate::checkOperate(LibraryMemberOperateCode::LIBRARY_DOC_GROUP__SORT);

        $libraryId = $this->request->libraryId;
        $libraryDocGroupId = $this->request->libraryDocGroupId;
        $parentGroupId = $this->input('parent_group_id/d', 0);
        $sort = $this->input('sort/d', -1);

        $docGroupSort = LibraryDocGroupSortLogic::make();
        Db::transaction(function () use ($docGroupSort, $libraryId, $libraryDocGroupId, $parentGroupId, $sort) {
            $docGroupSort->useLibraryDocGroup($libraryId, $libraryDocGroupId)->useOption($sort, $parentGroupId)->sort();
        });

        return $this->responseSuccess('修改成功');
    }

    /**
     * 文档分组信息
     */
    public function groupInfo() {
        $docGroupId = $this->request->libraryDocGroupId;
        $docInfo = LibraryDocGroupService::getLibraryDocGroupInfo($docGroupId, 'id,uid,library_id,pid,name,desc,sort');
        return $this->responseData($docInfo);
    }

}
