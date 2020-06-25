<?php

/*
 * YLibraryMemberModel Entity
 *
 * @Created: 2020-06-20 22:30:14
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\entity\model;

use app\entity\extend\BaseEntity;
use app\kernel\model\YLibraryMemberModel;

/**
 * @property int $id 自增id
 * @property int $library_id 文档库id
 * @property string $library_name 文档库名称(冗余字段)
 * @property string $library_alias 文档库别名
 * @property int $group_id 文档库分组id
 * @property int $sort 排序 从大到小
 * @property int $uid 成员uid
 * @property int $urole 成员角色
 * @property int $status 成员状态：0审核中 1正式成员 2已禁用
 * @property int $delete_time 删除时间 0表示未删除
 * @property int $apply_time 申请加入时间
 * @property int $create_time 创建时间
 */
class YLibraryMemberEntity extends BaseEntity {

    protected $model = YLibraryMemberModel::class;

}