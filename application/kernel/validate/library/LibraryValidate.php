<?php

/*
 * 文档库相关 Validate
 *
 * @Created: 2020-06-20 21:13:32
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\validate\library;

use app\kernel\model\YLibraryModel;
use app\kernel\validate\extend\BaseValidate;

class LibraryValidate extends BaseValidate {

    protected $rule = [
        'id'   => ['checkExistsLibrary'],
        'uid'  => ['require'],
        'name' => ['require', 'length' => '1,32'],
        'desc' => ['max' => 255],
    ];

    protected $message = [
        'uid.require'  => '文档库必需归属某个用户',
        'name.require' => '文档库名称不能为空',
        'name.length'  => '文档库名称字符长度在1~32个之间',
        'desc.max'     => '文档库简介最大长度为255个字符',
    ];

    protected $scene = [
        'create' => ['uid', 'name', 'desc'], // 文档库创建
        'modify' => ['id', 'name', 'desc'], // 文档库修改
        'remove' => ['id'], // 文档库删除
    ];

    /**
     * 验证文档库是否存在
     *
     * @param int $libraryId 文档库id
     * @return boolean|string
     */
    protected function checkExistsLibrary($libraryId) {
        if (empty($libraryId) || !YLibraryModel::existsOne(['id' => $libraryId])) {
            return '文档库不存在';
        }

        return true;
    }

}