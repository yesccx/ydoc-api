<?php

/*
 * 文档库分组相关 Validate
 *
 * @Created: 2020-06-20 17:24:20
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\validate\library;

use app\kernel\model\YLibraryGroupModel;
use app\kernel\validate\extend\BaseValidate;

class LibraryGroupValidate extends BaseValidate {

    protected $rule = [
        'id'   => ['checkExistsLibraryGroup'],
        'uid'  => ['require'],
        'name' => ['require', 'length' => '1,32'],
        'desc' => ['max' => 255],
    ];

    protected $message = [
        'uid.require'  => '文档库分组必需归属某个用户',
        'name.require' => '文档库分组名称不能为空',
        'name.length'  => '文档库分组名称字符长度在1~32个之间',
        'desc.max'     => '文档库分组简介最大长度为255个字符',
    ];

    protected $scene = [
        'create' => ['uid', 'name', 'desc'], // 文档库分组创建
        'modify' => ['id', 'name', 'desc'], // 文档库分组修改
        'remove' => ['id'], // 文档库分组删除
    ];

    /**
     * 验证文档库分组是否存在
     *
     * @param int $groupId 文档库分组id
     * @return boolean|string
     */
    protected function checkExistsLibraryGroup($groupId) {
        if (empty($groupId) || !YLibraryGroupModel::existsOne(['id' => $groupId])) {
            return '文档库分组不存在';
        }

        return true;
    }

}