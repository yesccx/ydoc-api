<?php

/*
 * YLibraryDocHistoryModel Entity
 *
 * @Created: 2020-07-03 18:35:56
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\entity\model;

use app\entity\extend\BaseEntity;
use app\kernel\model\YLibraryDocHistoryModel;

/**
 * @property int $id 文档id
 * @property int $library_id 文档库id
 * @property int $doc_id 文档id
 * @property int $uid 用户uid
 * @property int $group_id 文档分组id
 * @property string $title 文档标题
 * @property string $content 文档内容
 * @property string $editor 文档编辑器
 * @property int $sort 排序 从大到小
 * @property int $delete_time 删除时间 0表示未删除
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 */
class YLibraryDocHistoryEntity extends BaseEntity {

    protected $model = YLibraryDocHistoryModel::class;

}