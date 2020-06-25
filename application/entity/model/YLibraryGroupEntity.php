<?php

/*
 * YLibraryGroupModel Entity
 *
 * @Created: 2020-06-20 17:28:10
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\entity\model;

use app\entity\extend\BaseEntity;
use app\kernel\model\YLibraryGroupModel;

/**
 * @property int $id 分组id
 * @property int $uid 用户uid
 * @property string $name 分组名称
 * @property string $desc 分组简介
 * @property int $sort 排序 从大到小
 * @property int $delete_time 删除时间 0表示未删除
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 */
class YLibraryGroupEntity extends BaseEntity {

    protected $model = YLibraryGroupModel::class;

}