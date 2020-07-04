<?php

/*
 * y_library_doc_history（文档库文档历史记录表） Model
 *
 * @Created: 2020-07-03 16:03:53
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\model;

use app\kernel\model\extend\BaseModel;
use app\kernel\model\YUserModel;

class YLibraryDocHistoryModel extends BaseModel {

    protected $pk = 'id';

    protected $table = 'y_library_doc_history';

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

    // 模型关联：y_user
    public function userInfo() {
        return $this->hasOne(YUserModel::class, 'id', 'uid')->setEagerlyType(1);
    }

}