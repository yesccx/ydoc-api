<?php

/*
 * YLibraryShareModel Entity
 *
 * @Created: 2020-07-04 10:03:14
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\entity\model;

use app\entity\extend\BaseEntity;
use app\kernel\model\YLibraryShareModel;

/**
 * @property int $id 文档库分享id
 * @property int $uid 用户uid
 * @property int $library_id 文档库id
 * @property int $doc_id 文档id
 * @property string $share_code 分享码
 * @property string $share_name 分享名称
 * @property string $share_desc 分享简介
 * @property string $access_password 访问密码
 * @property int $access_count 访问计数
 * @property int $is_protected 是否受保护 0非保护（不需要访问密码） 1受保护（需要访问密码）
 * @property int $expire_time 过期时间 0为永不过期
 * @property int $status 文档库状态 0审核中 1启用 2禁用
 * @property int $delete_time 删除时间 0表示未删除
 * @property int $create_time 创建时间（分享时间）
 * @property int $update_time 更新时间
 */
class YLibraryShareEntity extends BaseEntity {

    protected $model = YLibraryShareModel::class;

}