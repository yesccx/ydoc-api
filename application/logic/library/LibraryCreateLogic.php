<?php

/*
 * 文档库创建 Logic
 *
 * @Created: 2020-06-20 20:52:52
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\library;

use app\constants\common\AppHookCode;
use app\constants\model\YLibraryMemberCode;
use app\entity\model\YLibraryEntity;
use app\exception\AppException;
use app\extend\common\AppHook;
use app\kernel\model\YLibraryModel;
use app\kernel\validate\library\LibraryValidate;
use app\logic\extend\BaseLogic;
use app\logic\libraryManager\LibraryMemberInviteLogic;

class LibraryCreateLogic extends BaseLogic {

    /**
     * 文档库实体信息
     *
     * @var YLibraryEntity
     */
    public $libraryEntity;

    /**
     * 使用文档库信息
     *
     * @param YLibraryEntity $libraryEntity
     * @return $this
     * @throws AppException
     */
    public function useLibrary(YLibraryEntity $libraryEntity) {
        // 校验数据合法性
        LibraryValidate::checkOrException($libraryEntity->toArray(), 'create');

        $this->libraryEntity = $libraryEntity;

        return $this;
    }

    /**
     * 创建文档库
     *
     * @return $this
     * @throws AppException
     */
    public function create() {
        $libraryInfo = YLibraryModel::create(
            $this->libraryEntity->toArray(),
            'uid,team_id,name,desc,cover,sort,status,create_time,update_time'
        );
        if (empty($libraryInfo)) {
            throw new AppException('文档库创建失败');
        }

        $this->libraryEntity = $libraryInfo->toEntity();

        AppHook::listen(AppHookCode::LIBRARY_CREATED, $this->libraryEntity);

        return $this;
    }

    /**
     * 初始化文档库成员
     *
     * @param int $libraryGroupId 文档库分组id
     * @return $this
     * @throws AppException
     */
    public function initLibraryMember($libraryGroupId = 0) {
        // 将创建人加入文档库
        try {
            LibraryMemberInviteLogic::make()
                ->useLibraryMember($this->libraryEntity->uid, $this->libraryEntity->id)
                ->useOption(YLibraryMemberCode::ROLE__CREATOR, $libraryGroupId)
                ->invite();
        } catch (AppException $e) {
            $e->throwAgain('邀请用户成为文档库成员失败');
        }

        return $this;
    }

}