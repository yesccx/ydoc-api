<?php

/*
 * 文档库中心 Controller
 *
 * @Created: 2020-06-20 14:20:24
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\controller\v1\library;

use app\constants\module\LibraryMemberOperateCode;
use app\entity\model\YLibraryEntity;
use app\extend\common\AppPagination;
use app\extend\common\AppQuery;
use app\extend\library\LibraryMemberOperate;
use app\extend\library\LibraryPreferenceHandler;
use app\kernel\base\AppBaseController;
use app\logic\library\LibraryCreateLogic;
use app\logic\library\LibraryModifyLogic;
use app\logic\library\LibraryRemoveLogic;
use app\logic\library\LibraryTransferLogic;
use app\service\library\LibraryService;
use think\Db;

class LibraryCenterController extends AppBaseController {

    protected $middleware = [
        \app\kernel\middleware\library\LibraryAuthMiddleware::class => [ // 文档库操作鉴权
            'only' => [
                'libraryInfo', 'libraryModify', 'libraryRemove', 'libraryTransfer', 'libraryPreference',
            ],
        ],
    ];

    /**
     * 文档库信息
     */
    public function libraryInfo() {
        $libraryId = $this->request->libraryId;
        $libraryInfo = LibraryService::getLibraryInfo($libraryId, 'id,uid,team_id,name,desc,create_time,update_time,cover');
        return $this->responseData($libraryInfo->toArray());
    }

    /**
     * 文档库列表
     */
    public function libraryList(AppPagination $pagination) {
        $groupId = $this->input('group_id/s', '');
        $searchKey = $this->input('search_key/s', '');

        // 查询条件
        $query = AppQuery::make();
        $query->when(!empty($groupId) && is_numeric($groupId), function ($squery) use ($groupId) {
            $squery->where(['group_id' => $groupId]);
        })->when(!empty($searchKey), function ($squery) use ($searchKey) {
            $squery->whereLike('library_name', "%{$searchKey}%");
        });

        // 获取列表数据
        $query->field('id,library_id,library_name,group_id,uid')->order('id', 'desc');
        $pageList = LibraryService::getMemberLibraryList($this->uid, $query, $pagination)->toArray();

        // 追加文档库信息
        if (!empty($pageList['list'])) {
            $pageList['list']->load(['library_info' => function ($squery) {
                $squery->field('id,uid,team_id,name,desc,sort,create_time,cover');
            }]);
        }

        return $this->responseData($pageList);
    }

    /**
     * 文档库集合
     */
    public function libraryCollection() {
        $searchKey = $this->input('search_key/s', '');

        // 查询条件
        $query = AppQuery::make();
        $query->when(!empty($searchKey), function ($squery) use ($searchKey) {
            $squery->whereLike('library_name', "%{$searchKey}%");
        });

        // 获取列表数据
        $query->field('id,library_id,library_name,group_id,uid,sort')->order('id', 'desc');
        $collection = LibraryService::getMemberLibraryCollection($this->uid, $query);

        // 追加文档库信息
        if (!empty($collection)) {
            $collection->load(['library_info' => function ($squery) {
                $squery->field('id,uid,team_id,name,desc,sort,create_time');
            }]);
        }

        return $this->responseData($collection);
    }

    /**
     * 文档库创建
     */
    public function libraryCreate() {
        $libraryGroupId = $this->input('group_id/d', 0);

        $libraryEntity = YLibraryEntity::inputMake(['name/s' => '', 'desc/s' => '', 'cover/s' => '']);
        $libraryEntity->uid = $this->uid;

        $libraryCreate = LibraryCreateLogic::make();
        Db::transaction(function () use ($libraryCreate, $libraryEntity, $libraryGroupId) {
            $libraryCreate->useLibrary($libraryEntity, $libraryGroupId)->create()->initLibraryMember();
        });

        return $this->responseData($libraryCreate->libraryEntity->toArray());
    }

    /**
     * 文档库修改
     */
    public function libraryModify() {
        LibraryMemberOperate::checkOperate(LibraryMemberOperateCode::LIBRARY__MODIFY);

        $libraryEntity = YLibraryEntity::inputMake(['name/s' => '', 'desc/s' => '', 'cover/s' => '']);
        $libraryEntity->id = $this->request->libraryId;

        $libraryModify = LibraryModifyLogic::make();
        Db::transaction(function () use ($libraryModify, $libraryEntity) {
            $libraryModify->useLibrary($libraryEntity)->modify();
        });

        return $this->responseSuccess('修改成功');
    }

    /**
     * 文档库删除
     */
    public function libraryRemove() {
        LibraryMemberOperate::checkOperate(LibraryMemberOperateCode::LIBRARY__REMOVE);

        $libraryId = $this->request->libraryId;
        $password = $this->input('password/s', '');
        if (empty($password)) {
            return $this->responseError('确认密码不能为空');
        }

        $libraryRemove = LibraryRemoveLogic::make();
        Db::transaction(function () use ($libraryRemove, $libraryId, $password) {
            $libraryRemove->useLibrary($libraryId)->confirm($password)->remove();
        });

        return $this->responseSuccess('删除成功');
    }

    /**
     * 文档库转让
     */
    public function libraryTransfer() {
        LibraryMemberOperate::checkOperate(LibraryMemberOperateCode::LIBRARY__TRANSFER);

        $libraryId = $this->request->libraryId;
        $memberId = $this->input('member_id/d', 0);
        $password = $this->input('password/s', '');
        if ($memberId <= 0 || empty($password)) {
            return $this->responseError('参数错误');
        }

        $libraryTransfer = LibraryTransferLogic::make();
        Db::transaction(function () use ($libraryTransfer, $libraryId, $memberId, $password) {
            $libraryTransfer->useLibraryMember($memberId, $libraryId)->confirm($password)->transfer();
        });

        return $this->responseSuccess('转让成功');
    }

}