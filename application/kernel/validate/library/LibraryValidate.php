<?php

/*
 * 文档库相关 Validate
 *
 * @Created: 2020-06-20 21:13:32
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\validate\library;

use app\kernel\validate\extend\BaseValidate;

class LibraryValidate extends BaseValidate {

    protected $rule = [
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
        'modify' => ['name', 'desc'], // 文档库修改
    ];

}