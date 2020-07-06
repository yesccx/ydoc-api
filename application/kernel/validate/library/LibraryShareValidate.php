<?php

/*
 * 文档库分享相关 Validate
 *
 * @Created: 2020-07-04 22:32:04
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\validate\library;

use app\kernel\model\YLibraryShareModel;
use app\kernel\validate\extend\BaseValidate;

class LibraryShareValidate extends BaseValidate {

    protected $rule = [
        'id'              => ['checkExistsLibraryShare'],
        'library_id'      => ['require'],
        'doc_id'          => ['require'],
        'share_code'      => ['require', 'length' => '3,32', 'validateLibraryShareCode'],
        'share_name'      => ['require', 'length' => '1,32'],
        'share_desc'      => ['max' => '255'],
        'access_password' => ['length' => '3,20', 'regex' => '/^[a-zA-z0-9@.-_!#]+$/'],
    ];

    protected $message = [
        'doc_id.require'         => '分享必需归属某个文档',
        'library_id.require'     => '分享必需归属某个文档库',
        'share_code.require'     => '分享码不能为空',
        'share_code.length'      => '分享码字符长度在3~32个之间',
        'share_name.require'     => '分享名称不能为空',
        'share_name.length'      => '分享名称字符长度在1~32个之间',
        'share_desc.max'         => '分享名称最大长度为255个字符',
        'access_password.length' => '分享密码长度为3-20个字符',
        'access_password.regex'  => '分享密码中不能有特殊字符',
    ];

    protected $scene = [
        'doc-share-create' => ['library_id', 'doc_id', 'share_code', 'share_name', 'share_desc', 'access_password'], // 文档分享创建
        'doc-share-update' => ['id', 'share_code', 'share_name', 'share_desc', 'access_password'], // 文档分享更新
        'library-share-create' => ['library_id', 'share_code', 'share_name', 'share_desc', 'access_password'], // 文档库分享创建
        'library-share-update' => ['id', 'share_code', 'share_name', 'share_desc', 'access_password'], // 文档库分享更新
    ];

    /**
     * 验证文档库/文档分享是否存在
     *
     * @param int $shareId 分享id
     * @return boolean|string
     */
    protected function checkExistsLibraryShare($shareId) {
        if (empty($shareId) || !YLibraryShareModel::existsOne(['id' => $shareId])) {
            return '分享不存在';
        }

        return true;
    }

    /**
     * 验证分享码是否已存在
     */
    protected function validateLibraryShareCode($shareCode, $rule, $data) {
        $mayShareInfo = YLibraryShareModel::where(['share_code' => $shareCode])->field('id')->find();
        if (empty($mayShareInfo) || (!empty($data['id']) && $mayShareInfo['id'] == $data['id'])) {
            return true;
        }
        return '分享码已被使用';
    }

}