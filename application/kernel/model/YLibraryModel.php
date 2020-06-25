<?php

/*
 * y_library（文档库表） Model
 *
 * @Created: 2020-06-18 22:03:40
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\model;

use app\kernel\model\extend\BaseModel;

class YLibraryModel extends BaseModel {

    protected $pk = 'id';

    protected $table = 'y_library';

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

    // 模型关联：y_library_member
    public function libraryMemberInfo() {
        return $this->hasOne(YLibraryMember::class, 'library_id', 'id')->setEagerlyType(1);
    }

}