<?php

/*
 * 用户消息操作鉴权 Middleware
 *
 * @Created: 2020-07-15 22:20:15
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\middleware\user;

use app\extend\common\AppRequest;
use app\extend\common\AppResponse;
use app\service\UserMessageService;
use think\Request;

class UserMessageAuthMiddleware {

    public function handle(Request $request, \Closure $next) {
        $messageId = AppRequest::input('message_id/d', 0);
        if (empty($messageId)) {
            return AppResponse::error('缺少必要参数消息id');
        }

        // 获取消息信息
        $messageInfo = UserMessageService::getMessageInfo($messageId, 'id,uid');
        if (empty($messageInfo) || $messageInfo['uid'] != $request->appSession->uid) {
            return AppResponse::error('消息不存在');
        }

        $request->messageId = $messageId;

        return $next($request);
    }

}