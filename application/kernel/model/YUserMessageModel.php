<?php

/*
 * y_user_message（用户消息表） Model
 *
 * @Created: 2020-07-15 22:05:10
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\model;

use app\kernel\model\extend\BaseModel;

class YUserMessageModel extends BaseModel {

    protected $pk = 'id';

    protected $table = 'y_user_message';

    protected $insert = ['create_time'];

    // 自动完成：创建时间
    protected function setCreateTimeAttr() {
        return time();
    }

}