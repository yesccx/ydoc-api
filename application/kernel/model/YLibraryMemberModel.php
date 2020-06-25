<?php

/*
 * y_library_member（文档库成员表） Model
 *
 * @Created: 2020-06-18 22:08:23
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\model;

use app\kernel\model\extend\BaseModel;

class YLibraryMemberModel extends BaseModel {

    protected $pk = 'id';

    protected $table = 'y_library_member';

    protected $insert = ['create_time'];

    protected $update = ['update_time'];

    // 自动完成：加入成员时间
    protected function setCreateTimeAttr() {
        return time();
    }

    // 自动完成：更新时间
    protected function setUpdateTimeAttr() {
        return time();
    }

    // 模型关联：y_library
    public function libraryInfo() {
        return $this->hasOne(YLibraryModel::class, 'id', 'library_id')->setEagerlyType(1);
    }

    // 模型关联：y_user
    public function userInfo() {
        return $this->hasOne(YUserModel::class, 'id', 'uid')->setEagerlyType(1);
    }

}