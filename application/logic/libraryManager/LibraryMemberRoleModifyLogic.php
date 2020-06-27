<?php

/*
 * 文档库成员角色修改 Logic
 *
 * @Created: 2020-06-25 10:01:23
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\libraryManager;

use app\constants\model\YLibraryMemberCode;
use app\entity\model\YLibraryMemberEntity;
use app\exception\AppException;
use app\kernel\model\YLibraryMemberModel;
use app\logic\extend\BaseLogic;
use app\service\library\LibraryService;

class LibraryMemberRoleModifyLogic extends BaseLogic {

    /**
     * 文档库成员角色
     *
     * @var int
     */
    protected $libraryMemberRole;

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
            throw new AppException('创建人的角色不能被更改');
        }

        $this->libraryMemberEntity = $libraryMember->toEntity();

        return $this;
    }

    /**
     * 指定文档库成员角色
     *
     * @param int $libraryRoleId 文档库成员角色id
     * @return $this
     * @throws AppException
     */
    public function useLibraryMemberRole($libraryRoleId) {
        if (!YLibraryMemberCode::make('urole')->has($libraryRoleId)) {
            throw new AppException('无效的角色值');
        }

        $this->libraryMemberRole = $libraryRoleId;

        return $this;
    }

    /**
     * 修改成员角色
     *
     * @return $this
     * @throws AppException
     */
    public function modify() {
        if (is_null($this->libraryMemberRole)) {
            throw new AppException('请指定一个角色值');
        }

        $updateRes = YLibraryMemberModel::update([
            'urole' => $this->libraryMemberRole, 'update_time' => time(),
        ], ['id' => $this->libraryMemberEntity->id]);
        if (empty($updateRes)) {
            throw new AppException('修改失败');
        }

        return $this;
    }

}