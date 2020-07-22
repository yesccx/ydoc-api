<?php

/*
 * y_user_config（用户配置表） Model
 *
 * @Created: 2020-07-21 12:31:55
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\model;

use app\extend\library\LibraryPreferenceHandler;
use app\kernel\model\extend\BaseModel;

class YUserConfigModel extends BaseModel {

    protected $pk = 'id';

    protected $table = 'y_user_config';

    protected $insert = ['create_time'];

    protected $auto = ['config'];

    // 自动完成：创建时间
    protected function setCreateTimeAttr() {
        return time();
    }

    // 获取器：配置参数
    protected function getConfigAttr($config) {
        return LibraryPreferenceHandler::handle(unserialize($config));
    }

    // 修改器：配置参数
    protected function setConfigAttr($config) {
        return serialize(LibraryPreferenceHandler::handle($config));
    }

}