<?php

/*
 * 文档库删除 Logic
 *
 * @Created: 2020-06-25 15:46:29
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\library;

use app\constants\common\AppHookCode;
use app\exception\AppException;
use app\extend\common\AppHook;
use app\kernel\model\YLibraryModel;
use app\kernel\validate\library\LibraryValidate;
use app\logic\extend\BaseLogic;
use app\service\UserService;
use app\utils\user\UserPasswordHandler;
use app\extend\library\LibraryOperateLog;
use app\constants\common\LibraryOperateCode;

class LibraryRemoveLogic extends BaseLogic {

    /**
     * 文档库id
     *
     * @var int
     */
    protected $libraryId;

    /**
     * 标识确认删除状态
     *
     * @var bool
     */
    protected $isConfirm = false;

    /**
     * 使用文档库
     *
     * @param int $libraryId 文档库id
     * @return $this
     */
    public function useLibrary($libraryId) {
        LibraryValidate::checkOrException(['id' => $libraryId], 'remove');

        $this->libraryId = $libraryId;

        return $this;
    }

    /**
     * 确认删除
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
     * 文档库删除
     *
     * @return $this
     * @throws AppException
     */
    public function remove() {
        if (!$this->isConfirm) {
            throw new AppException('删除文档库前需要先确认');
        }

        YLibraryModel::where(['id' => $this->libraryId])->softDelete();

        // 后续的清理动作在文档库删除事件中进行
        AppHook::listen(AppHookCode::LIBRARY_REMOVE_AFTER, $this->libraryId, false, false);

        // 文档库操作日志
        LibraryOperateLog::record($this->libraryId, LibraryOperateCode::LIBRARY_REMOVE);

        return $this;
    }

}