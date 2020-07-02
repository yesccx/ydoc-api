<?php

/*
 * 文档创建 Logic
 *
 * @Created: 2020-06-27 11:43:06
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\logic\libraryDoc;

use app\constants\common\AppHookCode;
use app\entity\model\YLibraryDocEntity;
use app\exception\AppException;
use app\extend\common\AppHook;
use app\extend\common\AppQuery;
use app\kernel\model\YLibraryDocGroupModel;
use app\kernel\model\YLibraryDocModel;
use app\kernel\validate\library\LibraryDocValidate;
use app\logic\extend\BaseLogic;

class LibraryDocCreateLogic extends BaseLogic {

    /**
     * 文档实体信息
     *
     * @var YLibraryDocEntity
     */
    public $libraryDocEntity;

    /**
     * 使用文档信息
     *
     * @param YLibraryDocEntity $libraryDocEntity
     * @return $this
     * @throws AppException
     */
    public function useLibraryDoc($libraryDocEntity) {
        LibraryDocValidate::checkOrException($libraryDocEntity->toArray(), 'create');

        $this->libraryDocEntity = $libraryDocEntity;

        // 计算新分组排序位置
        if (!$libraryDocEntity->hasFields('sort') || $libraryDocEntity->sort < 0) {
            $this->libraryDocEntity->sort = $this->computeDocSort();
        }

        return $this;
    }

    /**
     * 计算分组排序
     * PS: 取至文档库未尾分组排序值 + 步长
     *
     * @param int $step 默认间隔步长
     * @return int sort
     */
    protected function computeDocSort($step = 10000) {
        // 获取末尾分组排序值
        $lastGroup = YLibraryDocGroupModel::findOne(AppQuery::make(
            ['library_id' => $this->libraryDocEntity->library_id, 'pid' => $this->libraryDocEntity->group_id],
            'sort', 'sort desc'
        ));
        $lastGroupSort = $lastGroup ? intval($lastGroup['sort'] + $step) : 0;

        // 获取末尾文档排序值
        $lastDoc = YLibraryDocModel::findOne(AppQuery::make(
            ['library_id' => $this->libraryDocEntity->library_id, 'group_id' => $this->libraryDocEntity->group_id],
            'sort', 'sort desc'
        ));
        $lastDocSort = $lastDoc ? intval($lastDoc['sort'] + $step) : 0;

        return (empty($lastGroup) && empty($lastDoc)) ? $step : max($lastGroupSort, $lastDocSort);
    }

    /**
     * 创建文档
     *
     * @return $this
     * @throws AppException
     */
    public function create() {
        $libraryDoc = YLibraryDocModel::create($this->libraryDocEntity->toArray(), 'library_id,group_id,title,content,sort,create_time');
        if (empty($libraryDoc)) {
            throw new AppException('未知错误');
        }

        $this->libraryDocEntity = $libraryDoc->toEntity();

        AppHook::listen(AppHookCode::LIBRARY_DOC_CREATED);

        return $this;
    }

}