<?php

/*
 * 用户头像相关
 *
 * @Created: 2020-06-20 13:17:05
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\extend;

use app\entity\model\YUserEntity;
use app\exception\AppException;
use app\extend\common\AppQuery;
use app\extend\GenerateLetterImage;
use app\kernel\model\YUserModel;

class UserAvatar {

    /**
     * 头像存放目录
     *
     * @var string
     */
    protected $avatarPath = '';

    /**
     * 用户实体信息
     *
     * @var YUserEntity
     */
    protected $userEntity;

    public function __construct($uid) {
        $this->initUserInfo($uid);
        $this->avatarPath = checkMkdir(ROOT_STATIC_PATH . '/avatar');
    }

    /**
     * 初始化用户头像
     *
     * @param string $name 可选头像文字内容
     * @return void
     */
    public function initUserAvatar($name = '') {
        $name = $name ?: $this->userEntity->account;
        return $this->buildAvatar($this->userEntity->avatar, $name);
    }

    /**
     * 获取用户头像url地址
     * // FIXME: 生成头像url的方式待优化
     *
     * @return string
     */
    public function getUserAvatarUrl() {
        $avatarUrl = APP_ROOT_STATIC_URL . '/avatar/normal.png';
        if (!empty($this->userEntity->avatar) && file_exists($this->getAvatarFullName($this->userEntity->avatar))) {
            $avatarUrl = APP_ROOT_STATIC_URL . '/' . $this->getAvatarFullName($this->userEntity->avatar, 'avatar');
        }

        return $avatarUrl;
    }

    /**
     * 使用用户信息
     *
     * @param YUserEntity $userEntity
     * @return $this
     */
    public function useUser($userEntity) {
        $this->userEntity = $userEntity;
        return $this;
    }

    /**
     * 构建头像
     *
     * @param string $id 唯一标识
     * @param string $name 头像内容
     * @param string $path 保存路径，默认取配置路径
     * @return boolean
     */
    protected function buildAvatar($id, $name, $path = '') {
        $fullName = $this->getAvatarFullName($id, $path);

        // 初始化目录
        checkMkdir(pathinfo($fullName, PATHINFO_DIRNAME));

        return GenerateLetterImage::make()->run($name, $fullName, 400);
    }

    /**
     * 获取头像图片实际保存文件名
     *
     * @param string $id 唯一标识
     * @param string $path 保存路径，默认取配置路径
     * @return string 带绝对路径的文件名
     */
    protected function getAvatarFullName($id, $path = '') {
        $path = $path ?: $this->avatarPath;

        // 按标识名前两位分组
        $idGroup = substr($id, 0, 2);
        $groupPath = "{$path}/{$idGroup}";

        return "{$groupPath}/{$id}.png";
    }

    /**
     * 初始化用户信息
     *
     * @param integer $uid 用户id
     * @return void
     * @throws AppException
     */
    protected function initUserInfo($uid) {
        $userInfo = YUserModel::findOne(AppQuery::make(['id' => $uid], 'id,account,avatar'));
        if (empty($userInfo)) {
            throw new AppException('用户信息不存在');
        }

        return $this->useUser($userInfo->toEntity());
    }

}