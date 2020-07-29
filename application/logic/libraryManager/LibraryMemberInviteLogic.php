<?php

/*
 * 邀请用户成为文档库的成员 Logic
 *
 * @Created: 2020-06-20 22:19:05
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\libraryManager;

use app\constants\common\AppHookCode;
use app\constants\common\LibraryOperateCode;
use app\constants\model\YLibraryMemberCode;
use app\entity\model\YLibraryEntity;
use app\entity\model\YLibraryMemberEntity;
use app\entity\model\YUserEntity;
use app\exception\AppException;
use app\extend\common\AppHook;
use app\extend\library\LibraryOperateLog;
use app\kernel\model\YLibraryMemberModel;
use app\logic\extend\BaseLogic;
use app\service\library\LibraryGroupService;
use app\service\library\LibraryService;
use app\service\UserService;

class LibraryMemberInviteLogic extends BaseLogic {

    /**
     * 文档库实体信息
     *
     * @var YLibraryEntity
     */
    protected $libraryEntity;

    /**
     * 用户实体信息
     *
     * @var YUserEntity
     */
    protected $userEntity;

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
        // 获取文档库信息
        if (empty($libraryInfo = LibraryService::getLibraryInfo($libraryId, 'id,name'))) {
            throw new AppException('文档库不存在');
        }

        // 获取用户信息
        if (empty($userInfo = UserService::getUserInfo($uid, 'id,account,nickname'))) {
            throw new AppException('待邀请的用户不存在');
        }

        $this->libraryEntity = $libraryInfo->toEntity();
        $this->userEntity = $userInfo->toEntity();

        // 判断用户是否已加入当前文档库
        if ($this->checkLibraryMemberExists()) {
            throw new AppException('用户已是该文档库的成员');
        }

        // 准备文档库成员信息
        $this->libraryMemberEntity = YLibraryMemberEntity::make([
            'library_id'   => $this->libraryEntity->id,
            'library_name' => $this->libraryEntity->name,
            'uid'          => $this->userEntity->id,
            'urole'        => YLibraryMemberCode::ROLE__MEMBER,
        ]);

        return $this;
    }

    /**
     * 指定文档库成员初始信息
     *
     * @param int $urole 成员角色
     * @param int $libraryGroupId 文档库分组
     * @param string $libraryAlias 文档库别名
     * @return $this
     * @throws AppException
     */
    public function useOption($urole = 0, $libraryGroupId = 0, $libraryAlias = '') {
        if (!empty($urole) && YLibraryMemberCode::make('urole')->has($urole)) {
            $this->libraryMemberEntity->urole = $urole;
        }

        if (!empty($libraryGroupId) && !empty(LibraryGroupService::existsLibraryGroup($libraryGroupId, ['uid' => $this->userEntity->id]))) {
            $this->libraryMemberEntity->group_id = $libraryGroupId;
        }

        if (!empty($libraryAlias)) {
            $this->libraryMemberEntity->library_alias = $libraryAlias;
        }

        return $this;
    }

    /**
     * 邀请用户
     *
     * @return $this
     * @throws AppException
     */
    public function invite() {
        $libraryMemberEntity = $this->libraryMemberEntity;
        $libraryMemberEntity->apply_time = time();
        $libraryMemberEntity->status = YLibraryMemberCode::STATUS__ENABLED;

        $libraryMemberInfo = YLibraryMemberModel::create(
            $libraryMemberEntity->toArray(),
            'library_id,library_name,library_alias,group_id,sort,uid,urole,status,apply_time,create_time,update_time'
        );
        if (empty($libraryMemberInfo)) {
            throw new AppException('邀请用户失败');
        }
        $this->libraryMemberEntity = $libraryMemberInfo->toEntity();

        AppHook::listen(AppHookCode::LIBRARY_INVITE_AFTER, $this->libraryMemberEntity);

        // 文档库操作日志
        if ($libraryMemberEntity->urole === YLibraryMemberCode::ROLE__CREATOR) {
            LibraryOperateLog::record(
                $libraryMemberEntity->library_id, LibraryOperateCode::LIBRARY_INVITE, '初始化创始人', $this->libraryMemberEntity->toArray()
            );
        } else {
            $userInfo = UserService::getUserInfo($libraryMemberEntity->uid, 'nickname');
            LibraryOperateLog::record(
                $libraryMemberEntity->library_id, LibraryOperateCode::LIBRARY_INVITE, '用户：' . $userInfo['nickname'], $this->libraryMemberEntity->toArray()
            );
        }

        return $this;
    }

    /**
     * 判断用户是否已加入当前文档库
     *
     * @return bool
     */
    protected function checkLibraryMemberExists() {
        return LibraryService::existsLibraryMember($this->libraryEntity->id, $this->userEntity->id);
    }

}