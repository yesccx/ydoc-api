<?php

/*
 * 文档库管理相关 Controller
 *
 * @Created: 2020-06-21 10:20:43
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\controller\v1\library;

use app\constants\module\LibraryMemberOperateCode;
use app\extend\library\LibraryMemberOperate;
use app\kernel\base\AppBaseController;
use app\logic\libraryManager\LibraryMemberGroupModifyLogic;
use app\logic\libraryManager\LibraryMemberInviteLogic;
use app\logic\libraryManager\LibraryMemberRoleModifyLogic;
use app\logic\libraryManager\LibraryMemberStatusModifyLogic;
use app\logic\libraryManager\LibraryMemberUninviteLogic;
use app\service\LibraryManagerService;
use app\service\LibraryService;
use think\Db;

class LibraryManagerController extends AppBaseController {

    protected $middleware = [
        \app\kernel\middleware\library\LibraryAuthMiddleware::class => [ // 文档库操作鉴权
            'only' => [
                'libraryManagerInfo', 'libraryMemberGroupModify', 'libraryMemberCollect', 'libraryMemberSort',
                'libraryMemberInvite', 'libraryMemberStatusModify', 'libraryMemberRoleModify', 'libraryMemberUninvite'
            ],
        ],
    ];

    /**
     * 文档库管理信息
     */
    public function libraryManagerInfo() {
        $libraryId = $this->request->libraryId;

        $libraryInfo = LibraryService::getLibraryInfo($libraryId, 'id,uid,team_id,name,desc,create_time,update_time');
        $libraryMember = LibraryService::getLibraryMemberInfo($libraryId, $this->uid, 'group_id,uid,urole,apply_time');

        return $this->responseData([
            'libraryInfo'   => $libraryInfo,
            'libraryMember' => $libraryMember,
        ]);
    }

    /**
     * 成员文档库分组修改
     */
    public function libraryMemberGroupModify() {
        $libraryId = $this->request->libraryId;
        $groupId = $this->input('library_group_id/d', 0);
        $sort = $this->input('sort/f', -1);
        if ($groupId < 0) {
            return $this->responseError('参数错误');
        }

        $memberGroupModify = LibraryMemberGroupModifyLogic::make();
        Db::transaction(function () use ($memberGroupModify, $libraryId, $groupId, $sort) {
            $memberGroupModify->useLibraryMember($this->uid, $libraryId)->useLibraryGroup($groupId, $sort)->modify();
        });

        return $this->responseSuccess('修改成功');
    }

    /**
     * 文档库成员集合
     */
    public function libraryMemberCollect() {
        $collect = LibraryManagerService::getLibraryMemberCollect(
            $this->request->libraryId, 'id,library_id,uid,urole,status,apply_time,create_time,update_time'
        );
        return $this->responseData($collect);
    }

    /**
     * 文档库邀请用户
     * // TODO: 文档库邀请多个用户时，代码实现待优化（不能用foreach）
     */
    public function libraryMemberInvite() {
        LibraryMemberOperate::checkOperate(LibraryMemberOperateCode::LIBRARY_MEMBER__INVITE);

        $libraryId = $this->request->libraryId;
        $memberIds = $this->input('member_ids/a', []);
        if (empty($memberIds)) {
            return $this->responseError('存在无效的用户');
        }

        $memberInvite = LibraryMemberInviteLogic::make();
        Db::transaction(function () use ($memberInvite, $libraryId, $memberIds) {
            foreach ($memberIds as $memberId) {
                $memberInvite->useLibraryMember($memberId, $libraryId)->invite();
            }
        });

        return $this->responseSuccess('ok');
    }

    /**
     * 文档库成员移除
     */
    public function libraryMemberUninvite() {
        LibraryMemberOperate::checkOperate(LibraryMemberOperateCode::LIBRARY_MEMBER__REMOVE);

        $libraryId = $this->request->libraryId;
        $memberId = $this->input('member_id/d', 0);
        if ($memberId <= 0) {
            return $this->responseError('参数错误');
        }

        $memberUninvite = LibraryMemberUninviteLogic::make();
        Db::transaction(function () use ($memberUninvite, $libraryId, $memberId) {
            $memberUninvite->useLibraryMember($memberId, $libraryId)->uninvite();
        });

        return $this->responseSuccess('移除成功');
    }

    /**
     * 文档库成员状态调整
     */
    public function libraryMemberStatusModify() {
        LibraryMemberOperate::checkOperate(LibraryMemberOperateCode::LIBRARY_MEMBER__STATUS_MODIFY);

        $libraryId = $this->request->libraryId;
        $memberId = $this->input('member_id/d', 0);
        $status = $this->input('status/d', 0);
        if ($memberId <= 0) {
            return $this->responseError('参数错误');
        }

        $memberStatusModify = LibraryMemberStatusModifyLogic::make();
        Db::transaction(function () use ($memberStatusModify, $libraryId, $memberId, $status) {
            $memberStatusModify->useLibraryMember($memberId, $libraryId)->useMemberStatus($status)->modify();
        });

        return $this->responseSuccess('修改成功');
    }

    /**
     * 文档库成员角色调整
     */
    public function libraryMemberRoleModify() {
        LibraryMemberOperate::checkOperate(LibraryMemberOperateCode::LIBRARY_MEMBER__ROLE_MODIFY);

        $libraryId = $this->request->libraryId;
        $memberId = $this->input('member_id/d', 0);
        $roleId = $this->input('role_id/d', 0);
        if ($memberId <= 0 || $roleId <= 0) {
            return $this->responseError('参数错误');
        }

        $memberRoleModify = LibraryMemberRoleModifyLogic::make();
        Db::transaction(function () use ($memberRoleModify, $libraryId, $memberId, $roleId) {
            $memberRoleModify->useLibraryMember($memberId, $libraryId)->useMemberRole($roleId)->modify();
        });

        return $this->responseSuccess('修改成功');
    }

}