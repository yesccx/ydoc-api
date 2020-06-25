<?php

/*
 * 文档库成员移除 Logic
 *
 * @Created: 2020-06-25 10:39:36
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\libraryManager;

use app\constants\common\AppHookCode;
use app\constants\model\YLibraryMemberCode;
use app\entity\model\YLibraryMemberEntity;
use app\exception\AppException;
use app\extend\common\AppHook;
use app\kernel\model\YLibraryMemberModel;
use app\logic\extend\BaseLogic;
use app\service\LibraryService;

class LibraryMemberUninviteLogic extends BaseLogic {

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
            throw new AppException('创建人不能被移除');
        } else if ($libraryMember['uid'] == $this->uid) {
            throw new AppException('不能移除自己');
        }

        $this->libraryMemberEntity = $libraryMember->toEntity();

        return $this;
    }

    /**
     * 移除成员
     *
     * @return $this
     * @throws AppException
     */
    public function uninvite() {
        $deleteRes = YLibraryMemberModel::where(['id' => $this->libraryMemberEntity->id])->softDelete();
        if (empty($deleteRes)) {
            throw new AppException('移除失败');
        }

        AppHook::listen(AppHookCode::LIBRARY_MEMBER_UNINVITE, $this->libraryMemberEntity);

        return $this;
    }

}