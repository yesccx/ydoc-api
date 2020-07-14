<?php

/*
 * 文档库转让 Logic
 *
 * @Created: 2020-06-25 16:06:32
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\library;

use app\constants\common\LibraryOperateCode;
use app\constants\model\YLibraryMemberCode;
use app\entity\model\YLibraryEntity;
use app\entity\model\YLibraryMemberEntity;
use app\exception\AppException;
use app\extend\library\LibraryOperateLog;
use app\kernel\model\YLibraryMemberModel;
use app\kernel\model\YLibraryModel;
use app\logic\extend\BaseLogic;
use app\service\library\LibraryService;
use app\service\UserService;
use app\utils\user\UserPasswordHandler;

class LibraryTransferLogic extends BaseLogic {

    /**
     * 标识确认删除状态
     *
     * @var bool
     */
    protected $isConfirm = false;

    /**
     * 文档库实体信息
     *
     * @var YLibraryEntity
     */
    protected $libraryEntity;

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
            throw new AppException('该成员不存在');
        } else if ($libraryMember['status'] != YLibraryMemberCode::STATUS__ENABLED) {
            throw new AppException('该成员已被禁用');
        } else if ($uid == $this->uid) {
            throw new AppException('不能再次归属给自己');
        }

        // 获取文档库信息
        $libraryInfo = LibraryService::getLibraryInfo($libraryId, 'id,uid');

        $this->libraryEntity = $libraryInfo->toEntity();
        $this->libraryMemberEntity = $libraryMember->toEntity();

        return $this;
    }
    /**
     * 确认转让
     *
     * @param string $password 确认密码
     * @return $this
     * @throws AppException
     */
    public function confirm($password) {
        if (empty($password)) {
            throw new AppException('确认密码不能为空');
        }

        $user = UserService::getUserInfo($this->uid, 'password,password_salt');
        if (empty($user)) {
            throw new AppException('密码错误');
        } else if (!UserPasswordHandler::check($user['password'], $password, $user['password_salt'])) {
            throw new AppException('密码错误');
        }

        $this->isConfirm = true;

        return $this;
    }

    /**
     * 转让文档库
     *
     * @return $this
     * @throws AppException
     */
    public function transfer() {
        if (!$this->isConfirm) {
            throw new AppException('暂让文档库前需要先确认');
        } else if (empty($this->libraryMemberEntity->uid)) {
            throw new AppException('请指定一个文档库成员');
        }

        // 标识新成员角色
        $transferRes = YLibraryModel::where(['id' => $this->libraryMemberEntity->library_id])
            ->update(['uid' => $this->libraryMemberEntity->uid]);
        $roleRes = YLibraryMemberModel::where(['library_id' => $this->libraryEntity->id, 'uid' => $this->libraryMemberEntity->uid])
            ->update(['urole' => YLibraryMemberCode::ROLE__CREATOR]);

        // 重新定义老成员角色
        $lastRoleRes = YLibraryMemberModel::where(['library_id' => $this->libraryEntity->id, 'uid' => $this->libraryEntity->uid])
            ->update(['urole' => YLibraryMemberCode::ROLE__MEMBER]);

        if (empty($transferRes) || empty($roleRes) || empty($lastRoleRes)) {
            throw new AppException('未知错误');
        }

        // 文档库操作日志
        $userInfo = UserService::getUserInfo($this->libraryMemberEntity->uid, 'nickname');
        LibraryOperateLog::record($this->libraryEntity->id, LibraryOperateCode::LIBRARY_TRANSFER, '新总管理：' . $userInfo['nickname']);

        return $this;
    }

}