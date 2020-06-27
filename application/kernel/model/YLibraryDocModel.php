<?php

/*
 * y_library_doc（文档库文档表） Model
 *
 * @Created: 2020-06-18 22:05:40
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\model;

use app\kernel\model\extend\BaseModel;

class YLibraryDocModel extends BaseModel {

    protected $pk = 'id';

    protected $table = 'y_library_doc';

    protected $insert = ['create_time'];
    protected $update = ['update_time'];

    // 自动完成：创建时间
    protected function setCreateTimeAttr() {
        return time();
    }

    // 自动完成：修改时间
    protected function setUpdateTimeAttr() {
        return time();
    }

}