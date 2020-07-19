<?php

/*
 * 文档库创建 Logic
 *
 * @Created: 2020-06-20 20:52:52
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\library;

use app\constants\common\AppHookCode;
use app\constants\common\LibraryOperateCode;
use app\constants\model\YLibraryMemberCode;
use app\entity\model\YLibraryEntity;
use app\exception\AppException;
use app\extend\common\AppHook;
use app\extend\common\AppQuery;
use app\extend\library\LibraryOperateLog;
use app\kernel\model\YLibraryMemberModel;
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
     * @param int $libraryGroupId 文档库分组id
     * @return $this
     * @throws AppException
     */
    public function useLibrary(YLibraryEntity $libraryEntity, $libraryGroupId = 0) {
        // 校验数据合法性
        LibraryValidate::checkOrException($libraryEntity->toArray(), 'create');

        $this->libraryEntity = $libraryEntity;

        // 计算新分组排序位置
        if (!$libraryEntity->hasFields('sort') || $libraryEntity->sort < 0) {
            $this->libraryEntity->sort = $this->computeDocSort($libraryGroupId);
        }

        return $this;
    }

    /**
     * 计算排序
     * PS: 取至文档库未尾分组排序值 + 步长
     *
     * @param int $libraryGroupId 文档库分组id
     * @param int $step 默认间隔步长
     * @return int sort
     */
    protected function computeDocSort($libraryGroupId = 0, $step = 10000) {
        // 获取末尾分组排序值
        $lastGroup = YLibraryMemberModel::findOne(AppQuery::make(
            ['uid' => $this->libraryEntity->uid, 'group_id' => $libraryGroupId],
            'sort', 'sort desc'
        ));
        $lastGroupSort = $lastGroup ? intval($lastGroup['sort'] + $step) : 0;

        return empty($lastGroup) ? $step : $lastGroupSort;
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

        // 文档库操作日志
        LibraryOperateLog::record($this->libraryEntity->id, LibraryOperateCode::LIBRARY_CREATE, '', $this->libraryEntity->toArray());

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
        $libraryGroupId = $libraryGroupId ?: $this->libraryGroupId;

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