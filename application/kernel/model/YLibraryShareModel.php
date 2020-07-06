<?php

/*
 * y_library_share（文档库分享表） Model
 *
 * @Created: 2020-07-04 09:16:22
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\model;

use app\kernel\model\extend\BaseModel;
use app\kernel\model\YLibraryDocModel;
use app\kernel\model\YLibraryModel;
use app\kernel\model\YUserModel;

class YLibraryShareModel extends BaseModel {

    protected $pk = 'id';

    protected $table = 'y_library_share';

    protected $insert = ['create_time'];

    protected $update = ['update_time'];

    protected $auto = ['is_protected'];

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

    // 模型关联：y_library
    public function libraryInfo() {
        return $this->hasOne(YLibraryModel::class, 'id', 'library_id')->setEagerlyType(1);
    }

    // 模型关联：y_library_doc
    public function libraryDocInfo() {
        return $this->hasOne(YLibraryDocModel::class, 'id', 'doc_id')->setEagerlyType(1);
    }

    // 自动完成：是否受保护（根据是否设置了访问密码来决定）
    protected function setIsProtectedAttr($isProtected, $data) {
        return empty($data['access_password']) ? 0 : 1;
    }

}