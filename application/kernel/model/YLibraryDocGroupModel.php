<?php

/*
 * y_library_doc_group（文档库文档分组表） Model
 *
 * @Created: 2020-06-18 22:06:33
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\model;

use app\kernel\model\extend\BaseModel;

class YLibraryDocGroupModel extends BaseModel {

    protected $pk = 'id';

    protected $table = 'y_library_doc_group';

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
