<?php

/*
 * y_library_doc_template（文档库文档模板表） Model
 *
 * @Created: 2020-06-28 12:41:02
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\model;

use app\kernel\model\extend\BaseModel;

class YLibraryDocTemplateModel extends BaseModel {

    protected $pk = 'id';

    protected $table = 'y_library_doc_template';

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