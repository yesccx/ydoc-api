<?php

/*
 * YLibraryModel Entity
 *
 * @Created: 2020-06-20 20:53:37
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\entity\model;

use app\entity\extend\BaseEntity;
use app\kernel\model\YLibraryModel;

/**
 * @property int $id 文档库id
 * @property int $uid 用户uid
 * @property int $team_id 团队id
 * @property string $name 文档库名称
 * @property string $desc 文档库简介
 * @property string $cover 文档库封面图片url
 * @property int $sort 排序 从大到小
 * @property int $status 文档库状态 1正常 2归档
 * @property int $delete_time 删除时间 0表示未删除
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 */
class YLibraryEntity extends BaseEntity {

    protected $model = YLibraryModel::class;

}