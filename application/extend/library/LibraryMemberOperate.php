<?php

/*
 * 文档库成员操作实例
 *
 * @Created: 2020-06-24 19:22:10
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\extend\library;

use app\constants\model\YLibraryMemberCode;
use app\constants\module\LibraryMemberOperateCode;
use app\entity\model\YLibraryEntity;
use app\entity\model\YLibraryMemberEntity;
use app\exception\AppException;
use app\service\library\LibraryService;

class LibraryMemberOperate {

    /**
     * 实例对象
     *
     * @var static
     */
    private static $instance = null;

    /**
     * 文档库成员实体信息
     *
     * @var YLibraryMemberEntity
     */
    public $libraryMemberEntity;

    /**
     * 文档库实体信息
     *
     * @var YLibraryEntity
     */
    public $libraryEntity;

    public function __construct() {

    }

    /**
     * 单例
     *
     * @return static
     */
    public static function make() {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    /**
     * 验证操作是否在权限范围内
     *
     * @param string $operate 操作关键字
     * @param bool $catchException 是否捕捉异常
     * @return bool
     * @throws AppException
     */
    public static function checkOperate($operate, $catchException = true) {
        try {
            return static::make()->check($operate);
        } catch (AppException $e) {
            if ($catchException) {
                throw $e;
            } else {
                return $e->getMessage();
            }
        }
    }

    /**
     * 初始化
     *
     * @return $this
     * @throws AppException
     */
    public function init($libraryId, $uid) {
        // 获取文档库信息
        $libraryInfo = LibraryService::getLibraryInfo($libraryId, 'id');
        if (empty($libraryInfo)) {
            throw new AppException('文档库不存在');
        }

        // 判断当前用户是否有查看的权限
        $libraryMember = LibraryService::getLibraryMemberInfo($libraryId, $uid, 'urole,status');
        if (empty($libraryMember)) {
            throw new AppException('无操作权限');
        } else if ($libraryMember['status'] == YLibraryMemberCode::STATUS__NORMAL) {
            throw new AppException('还未通过审核，暂时无法访问');
        } else if ($libraryMember['status'] == YLibraryMemberCode::STATUS__DISABLED) {
            throw new AppException('操作权限已被禁用');
        }

        $this->libraryEntity = $libraryInfo->toEntity();
        $this->libraryMemberEntity = $libraryMember->toEntity();

        return $this;
    }

    /**
     * 验证操作是否在权限范围内
     *
     * @param string $operate 操作关键字
     * @return bool
     * @throws AppException
     */
    public function check($operate) {
        $operateRange = $this->getRoleOperateRange($this->libraryMemberEntity->urole);
        if (!in_array($operate, $operateRange)) {
            throw new AppException('无操作权限');
        }
        return true;
    }

    /**
     * 获取文档库角色可用的操作范围
     *
     * @param int|string $roleId 角色id
     * @return array 操作范围
     */
    protected function getRoleOperateRange($roleId) {
        $operateRange = [
            YLibraryMemberCode::ROLE__CREATOR => [
                LibraryMemberOperateCode::LIBRARY__MODIFY,
                LibraryMemberOperateCode::LIBRARY__PERMANENT,
                LibraryMemberOperateCode::LIBRARY__REMOVE,
                LibraryMemberOperateCode::LIBRARY__TRANSFER,
                LibraryMemberOperateCode::LIBRARY_MEMBER__INVITE,
                LibraryMemberOperateCode::LIBRARY_MEMBER__REMOVE,
                LibraryMemberOperateCode::LIBRARY_MEMBER__ROLE_MODIFY,
                LibraryMemberOperateCode::LIBRARY_MEMBER__STATUS_MODIFY,
                LibraryMemberOperateCode::LIBRARY_DOC_GROUP__CREATE,
                LibraryMemberOperateCode::LIBRARY_DOC_GROUP__MODIFY,
                LibraryMemberOperateCode::LIBRARY_DOC_GROUP__REMOVE,
                LibraryMemberOperateCode::LIBRARY_DOC_GROUP__SORT,
                LibraryMemberOperateCode::LIBRARY_DOC__CREATE,
                LibraryMemberOperateCode::LIBRARY_DOC__MODIFY,
                LibraryMemberOperateCode::LIBRARY_DOC__REMOVE,
                LibraryMemberOperateCode::LIBRARY_DOC__SORT,
            ],
            YLibraryMemberCode::ROLE__MANAGER => [
                LibraryMemberOperateCode::LIBRARY__MODIFY,
                LibraryMemberOperateCode::LIBRARY_MEMBER__INVITE,
                LibraryMemberOperateCode::LIBRARY_MEMBER__REMOVE,
                LibraryMemberOperateCode::LIBRARY_MEMBER__ROLE_MODIFY,
                LibraryMemberOperateCode::LIBRARY_MEMBER__STATUS_MODIFY,
                LibraryMemberOperateCode::LIBRARY_DOC_GROUP__CREATE,
                LibraryMemberOperateCode::LIBRARY_DOC_GROUP__MODIFY,
                LibraryMemberOperateCode::LIBRARY_DOC_GROUP__REMOVE,
                LibraryMemberOperateCode::LIBRARY_DOC_GROUP__SORT,
                LibraryMemberOperateCode::LIBRARY_DOC__CREATE,
                LibraryMemberOperateCode::LIBRARY_DOC__MODIFY,
                LibraryMemberOperateCode::LIBRARY_DOC__REMOVE,
                LibraryMemberOperateCode::LIBRARY_DOC__SORT,
            ],
            YLibraryMemberCode::ROLE__MEMBER  => [
                LibraryMemberOperateCode::LIBRARY_DOC_GROUP__CREATE,
                LibraryMemberOperateCode::LIBRARY_DOC_GROUP__MODIFY,
                LibraryMemberOperateCode::LIBRARY_DOC_GROUP__REMOVE,
                LibraryMemberOperateCode::LIBRARY_DOC_GROUP__SORT,
                LibraryMemberOperateCode::LIBRARY_DOC__CREATE,
                LibraryMemberOperateCode::LIBRARY_DOC__MODIFY,
                LibraryMemberOperateCode::LIBRARY_DOC__REMOVE,
                LibraryMemberOperateCode::LIBRARY_DOC__SORT,
            ],
            YLibraryMemberCode::ROLE__GUEST   => [],
        ];

        return $operateRange[$roleId] ?? [];
    }

}