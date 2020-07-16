<?php

/*
 * 用户消息相关 Service
 *
 * @Created: 2020-07-15 22:09:44
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\service;

use app\constants\model\YUserMessageCode;
use app\kernel\model\YUserMessageModel;

class UserMessageService {

    /**
     * 获取用户消息列表
     *
     * @param int $uid 用户uid
     * @param Query|string|null $query 查询器
     * @param AppPagination|null $pagination 分页对象
     * @return AppPagination|null
     */
    public static function getUserMessageList($uid, $query = null, $pagination = null) {
        $query = YUserMessageModel::useQuery($query)->where(['uid' => $uid]);
        return $query->pageSelect($pagination);
    }

    /**
     * 获取用户消息数统计
     *
     * @param int $uid 用户uid
     * @param int $status 统计的消息状态
     * @return int
     */
    public static function getUserMessageCount($uid, $status = 1) {
        return YUserMessageModel::where(['uid' => $uid])->when($status >= 0, function ($squery) use ($status) {
            $squery->where('status', $status);
        })->count();
    }

    /**
     * 获取消息信息
     *
     * @param int $messageId 消息id
     * @param Query|string|null $query 查询器
     * @return YUserMessageModel|null
     */
    public static function getMessageInfo($messageId, $query = null) {
        $query = YUserMessageModel::useQuery($query)->where(['id' => $messageId]);
        return $query->find();
    }

    /**
     * 标记消息已读
     *
     * @param int $messageId 消息id
     * @return mixed
     */
    public static function readMessage($messageId) {
        return YUserMessageModel::update([
            'status'    => YUserMessageCode::STATUS__READ,
            'read_time' => time(),
        ], ['id' => $messageId]);
    }

    /**
     * 标记消息未读
     *
     * @param int $messageId 消息id
     * @return mixed
     */
    public static function unreadMessage($messageId) {
        return YUserMessageModel::update([
            'status'    => YUserMessageCode::STATUS__UNREAD,
            'read_time' => 0,
        ], ['id' => $messageId]);
    }

    /**
     * 标记所有消息已读
     *
     * @param int $uid 用户uid
     * @return mixed
     */
    public static function readMessageAll($uid) {
        return YUserMessageModel::update([
            'status'    => YUserMessageCode::STATUS__READ,
            'read_time' => time(),
        ], ['uid' => $uid]);
    }

    /**
     * 删除消息
     *
     * @param int $messageId 消息id
     * @return mixed
     */
    public static function removeMessage($messageId) {
        return YUserMessageModel::where(['id' => $messageId])->softDelete();
    }

}