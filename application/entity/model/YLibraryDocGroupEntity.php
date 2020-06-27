<?php

/*
 * YLibraryDocGroupModel Entity
 *
 * @Created: 2020-06-20 22:34:30
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\entity\model;

use app\entity\extend\BaseEntity;
use app\kernel\model\YLibraryDocGroupModel;

/**
 * @property int $id 文档分组id
 * @property int $uid 用户uid
 * @property int $library_id 文档库id
 * @property int $pid 上级分组id
 * @property string $name 分组名称
 * @property string $desc 分组简介
 * @property int $sort 排序 从大到小
 * @property int $delete_time 删除时间 0表示未删除
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 */
class YLibraryDocGroupEntity extends BaseEntity {

    protected $model = YLibraryDocGroupModel::class;

}