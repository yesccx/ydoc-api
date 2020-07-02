<?php

/*
 * 文档模板相关 Validate
 *
 * @Created: 2020-06-30 10:39:39
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\validate\library;

use app\kernel\model\YLibraryDocTemplateModel;
use app\kernel\validate\extend\BaseValidate;

class LibraryDocTemplateValidate extends BaseValidate {

    protected $rule = [
        'id'           => ['checkExistsLibraryDocTemplate'],
        'name'         => ['require', 'length' => '1,32'],
        'introduction' => ['length' => '1,255'],
        'uid'          => ['require'],
    ];

    protected $message = [
        'name.require'        => '文档模板名称不能为空',
        'name.length'         => '文档模板名称字符长度在1~32个之间',
        'introduction.length' => '文档模板简介字符长度在1~255个之间',
        'uid.require'         => '文档模板必需归属某个用户',
    ];

    protected $scene = [
        'create' => ['uid', 'name', 'introduction'], // 文档模板创建
        'modify' => ['id', 'name', 'introduction'], // 文档模板修改
        'remove' => ['id'], // 文档模板删除
    ];

    /**
     * 验证文档模板是否存在
     *
     * @param int $libraryDocTemplateId 文档模板id
     * @return boolean|string
     */
    protected function checkExistsLibraryDocTemplate($libraryDocTemplateId) {
        if (empty($libraryDocTemplateId) || !YLibraryDocTemplateModel::existsOne(['id' => $libraryDocTemplateId])) {
            return '文档不存在';
        }

        return true;
    }

}