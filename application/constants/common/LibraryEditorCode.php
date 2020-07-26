<?php

/*
 * 文档编辑器 Constant
 *
 * @Created: 2020-07-24 12:44:10
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\constants\common;

use app\constants\extend\BaseCode;

class LibraryEditorCode extends BaseCode {

    // 编辑器：text(纯文本)
    const EDITOR_TEXT = 'text';

    // 编辑器：html(tinycme富文本)
    const EDITOR_HTML = 'html';

    // 编辑器：markdown(vditor)
    const EDITOR_MARKDOWN = 'markdown';

    // 编辑器：默认
    const EDITOR_DEFAULT = 'markdown';

}