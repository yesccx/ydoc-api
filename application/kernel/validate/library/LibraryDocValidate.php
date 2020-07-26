<?php

/*
 * 文档相关 Validate
 *
 * @Created: 2020-06-27 11:36:58
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\validate\library;

use app\constants\common\LibraryEditorCode;
use app\kernel\model\YLibraryDocModel;
use app\kernel\validate\extend\BaseValidate;

class LibraryDocValidate extends BaseValidate {

    protected $rule = [
        'id'         => ['checkExistsLibraryDoc'],
        'library_id' => ['require'],
        'title'      => ['require', 'length' => '1,32'],
        'editor'     => ['require', 'checkEditorAllow'],
    ];

    protected $message = [
        'title.require'      => '文档标题不能为空',
        'title.length'       => '文档标题字符长度在1~32个之间',
        'library_id.require' => '文档必需归属某个文档库',
        'editor.require'     => '文档必需使用一个编辑器',
    ];

    protected $scene = [
        'create' => ['library_id', 'title', 'editor'], // 文档创建
        'modify' => ['id', 'title', 'editor'], // 文档修改
        'remove' => ['id'], // 文档删除
    ];

    /**
     * 验证文档是否存在
     *
     * @param int $libraryDocId 文档id
     * @return boolean|string
     */
    protected function checkExistsLibraryDoc($libraryDocId) {
        if (empty($libraryDocId) || !YLibraryDocModel::existsOne(['id' => $libraryDocId])) {
            return '文档不存在';
        }

        return true;
    }

    /**
     * 验证文档编辑器是否有效
     *
     * @param string $editor 编辑器关键字
     * @return boolean|string
     */
    protected function checkEditorAllow($editor) {
        $allowed = [LibraryEditorCode::EDITOR_HTML, LibraryEditorCode::EDITOR_MARKDOWN, LibraryEditorCode::EDITOR_TEXT];
        if (!in_array($editor, $allowed)) {
            return '无效的编辑器类型';
        }
        return true;
    }

}