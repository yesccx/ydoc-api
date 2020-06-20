<?php

/*
 * Base Model
 *
 * @Created: 2020-06-18 21:59:00
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\model\extend;

use think\Model;
use think\model\concern\SoftDelete;

abstract class BaseModel extends Model {

    use SoftDelete, ModelCommon, QueryCommon, ModelEntity;

    // 开启软删除
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;

}