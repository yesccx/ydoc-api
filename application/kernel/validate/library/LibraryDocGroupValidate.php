<?php

/*
 * 文档分组相关 Validate
 *
 * @Created: 2020-06-25 22:33:26
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\validate\library;

use app\kernel\model\YLibraryDocGroupModel;
use app\kernel\validate\extend\BaseValidate;
use app\service\library\LibraryDocGroupService;

class LibraryDocGroupValidate extends BaseValidate {

    protected $rule = [
        'id'         => ['checkExistsLibraryDocGroup'],
        'library_id' => ['require'],
        'name'       => ['require', 'length' => '1,32'],
        'desc'       => ['max' => 255],
        'pid'        => ['checkParentGroup'],
    ];

    protected $message = [
        'library_id.require' => '文档分组必需归属某个文档库',
        'name.require'       => '文档分组名称不能为空',
        'name.length'        => '文档分组名称字符长度在1~32个之间',
        'desc.max'           => '文档分组简介最大长度为255个字符',
    ];

    protected $scene = [
        'create' => ['library_id', 'name', 'desc', 'pid'], // 文档分组创建
        'modify' => ['id', 'name', 'desc', 'pid'], // 文档分组修改
        'sort'   => ['id', 'pid'], // 文档分组排序
        'remove' => ['id'], // 文档分组删除
    ];

    /**
     * 验证文档分组的上级分组
     *
     * @param int $groupId 上级分组id
     * @return boolean|string
     */
    protected function checkParentGroup($pid, $rule, $groupInfo) {
        if (empty($pid)) {
            return true;
        }

        $parentGroupInfo = LibraryDocGroupService::getLibraryDocGroupInfo($pid, 'library_id');
        if (empty($parentGroupInfo)) {
            return '上级分组不存在';
        } else if ($parentGroupInfo['library_id'] != $groupInfo['library_id']) {
            return '上级分组无效';
        }

        return true;
    }

    /**
     * 验证文档分组是否存在
     *
     * @param int $groupId 文档分组id
     * @return boolean|string
     */
    protected function checkExistsLibraryDocGroup($groupId) {
        if (empty($groupId) || !YLibraryDocGroupModel::existsOne(['id' => $groupId])) {
            return '文档分组不存在';
        }

        return true;
    }

}