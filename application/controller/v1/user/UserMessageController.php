<?php

/*
 * 用户消息相关 Controller
 *
 * @Created: 2020-07-15 22:07:24
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\controller\v1\user;

use app\extend\common\AppPagination;
use app\extend\common\AppQuery;
use app\kernel\base\AppBaseController;
use app\kernel\middleware\user\UserMessageAuthMiddleware;
use app\kernel\model\YUserMessageModel;
use app\service\UserMessageService;

class UserMessageController extends AppBaseController {

    protected $middleware = [
        \app\kernel\middleware\user\UserMessageAuthMiddleware::class => [ // 用户消息操作鉴权
            'only' => [
                'messageInfo', 'messageRead', 'messageUnread', 'messageRemove',
            ],
        ],
    ];

    /**
     * 消息列表
     */
    public function messageList(AppPagination $pagination) {
        $searchKey = $this->input('search_key/s', '');
        $searchStatus = $this->input('search_status/d', -1);

        // 查询条件
        $query = AppQuery::make();
        $query->when($searchKey, function ($squery) use ($searchKey) {
            $squery->whereLike('title', "%{$searchKey}%");
        })->when($searchStatus >= 0, function ($squery) use ($searchStatus) {
            $squery->where('status', $searchStatus == 1 ? 1 : 0);
        });

        // 获取列表数据
        $query->field('id,title,create_time,status')->order('id', 'desc');
        $pageList = UserMessageService::getUserMessageList($this->uid, $query, $pagination)->toArray();

        return $this->responseData($pageList);
    }

    /**
     * 消息详情
     */
    public function messageInfo() {
        $messageId = $this->request->messageId;

        // 同时标记消息已读
        UserMessageService::readMessage($messageId);
        $messageInfo = UserMessageService::getMessageInfo($messageId, 'id,title,content,create_time,status');

        return $this->responseData($messageInfo);
    }

    /**
     * 获取消息数统计
     */
    public function messageCount() {
        $status = $this->input('status/d', 1);

        $unreadCount = UserMessageService::getUserMessageCount($this->uid, $status);
        return $this->responseData(['count' => $unreadCount]);
    }

    /**
     * 获取消息数所有统计
     */
    public function messageCountAll() {
        $unreadCount = UserMessageService::getUserMessageCount($this->uid, 0);
        $readCount = UserMessageService::getUserMessageCount($this->uid, 1);
        $allCount = $unreadCount + $readCount;

        return $this->responseData(['-1' => $allCount, '0' => $unreadCount, '1' => $readCount]);
    }

    /**
     * 消息标记已读
     */
    public function messageRead() {
        $messageId = $this->request->messageId;

        $res = UserMessageService::readMessage($messageId);
        if (empty($res)) {
            return $this->responseError('标记已读失败');
        }

        return $this->responseSuccess('ok');
    }

    /**
     * 消息标记未读
     */
    public function messageUnread() {
        $messageId = $this->request->messageId;

        $res = UserMessageService::unreadMessage($messageId);
        if (empty($res)) {
            return $this->responseError('标记未读失败');
        }

        return $this->responseSuccess('ok');
    }

    /**
     * 所有消息标记已读
     */
    public function messageReadAll() {
        UserMessageService::readMessageAll($this->uid);
        return $this->responseSuccess('ok');
    }

    /**
     * 消息删除
     */
    public function messageRemove() {
        $messageId = $this->request->messageId;

        $res = UserMessageService::removeMessage($messageId);
        if (empty($res)) {
            return $this->responseError('删除失败');
        }

        return $this->responseSuccess('ok');
    }

}