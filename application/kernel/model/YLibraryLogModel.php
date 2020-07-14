<?php

/*
 * y_library_log（文档库相关日志） Model
 *
 * @Created: 2020-06-22 19:35:56
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\model;

use app\kernel\model\extend\BaseModel;
use app\kernel\model\YLibraryModel;
use app\kernel\model\YUserModel;
use app\extend\library\LibraryOperateLogMessage;

class YLibraryLogModel extends BaseModel {

    protected $pk = 'id';

    protected $table = 'y_library_log';

    protected $insert = ['create_time'];

    protected $type = [
        'operate_params' => 'array',
    ];

    // 自动完成：创建时间
    protected function setCreateTimeAttr() {
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

    /**
     * 获取器：操作说明解析
     */
    public function getOperateMessageAttr($value, $data) {
        return LibraryOperateLogMessage::parse($data['operate_type']);
    }

}