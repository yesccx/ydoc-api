<?php

/*
 * y_library_group（文档库分组表） Model
 *
 * @Created: 2020-06-18 22:07:30
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\model;

use app\kernel\model\extend\BaseModel;

class YLibraryGroupModel extends BaseModel {

    protected $pk = 'id';

    protected $table = 'y_library_group';

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
