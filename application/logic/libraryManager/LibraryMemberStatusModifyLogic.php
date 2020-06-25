<?php

/*
 * 文档库成员状态修改 Logic
 *
 * @Created: 2020-06-25 09:48:49
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\libraryManager;

use app\constants\model\YLibraryMemberCode;
use app\entity\model\YLibraryMemberEntity;
use app\exception\AppException;
use app\kernel\model\YLibraryMemberModel;
use app\logic\extend\BaseLogic;
use app\service\LibraryService;

class LibraryMemberStatusModifyLogic extends BaseLogic {

    /**
     * 文档库成员实体信息
     *
     * @var YLibraryMemberEntity
     */
    protected $libraryMemberEntity;

    /**
     * 使用文档库成员
     *
     * @param int $uid 用户uid
     * @param int $libraryId 文档库id
     * @return $this
     * @throws AppException
     */
    public function useLibraryMember($uid, $libraryId) {
        // 准备文档库成员信息
        $libraryMember = LibraryService::getLibraryMemberInfo($libraryId, $uid, 'id,library_id,uid,status,urole');
        if (empty($libraryMember)) {
            throw new AppException('文档库成员不存在');
        } else if ($libraryMember['urole'] == YLibraryMemberCode::ROLE__CREATOR) {
            throw new AppException('创建人的状态不能被更改');
        }

        $this->libraryMemberEntity = $libraryMember->toEntity();

        return $this;
    }

    /**
     * 指定成员状态
     *
     * @param int $status 状态值
     * @return $this
     * @throws AppException
     */
    public function useMemberStatus($status) {
        if (!YLibraryMemberCode::make('status')->has($status)) {
            throw new AppException('无效的状态值');
        }

        $this->libraryMemberEntity->status = $status;

        return $this;
    }

    /**
     * 修改成员状态
     *
     * @return $this
     * @throws AppException
     */
    public function modify() {
        $updateRes = YLibraryMemberModel::update([
            'status' => $this->libraryMemberEntity->status, 'update_time' => time(),
        ], ['id' => $this->libraryMemberEntity->id]);
        if (empty($updateRes)) {
            throw new AppException('修改失败');
        }

        return $this;
    }

}