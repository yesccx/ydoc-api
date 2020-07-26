<?php

/*
 * YLibraryDocTemplateModel Entity
 *
 * @Created: 2020-06-30 10:26:47
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\entity\model;

use app\entity\extend\BaseEntity;
use app\kernel\model\YLibraryDocTemplateModel;

/**
 * @property int $id 文档模板id
 * @property int $uid 创建人uid
 * @property string $name 模板名称
 * @property string $introduction 模板介绍
 * @property string $content 模板内容
 * @property string $editor 模板编辑器
 * @property int $delete_time 删除时间 0表示未删除
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 */
class YLibraryDocTemplateEntity extends BaseEntity {

    protected $model = YLibraryDocTemplateModel::class;

}