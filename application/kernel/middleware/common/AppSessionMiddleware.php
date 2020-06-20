<?php

/*
 * AppSession Middleware
 *
 * @Created: 2020-06-18 14:35:47
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\middleware\common;

use app\exception\AppException;
use app\extend\common\AppResponse;
use app\extend\session\AppSessionHandler;
use think\Request;

class AppSessionMiddleware {

    public function handle(Request $request, \Closure $next) {
        if (empty($token = $request->header('token'))) {
            return AppResponse::error('缺少参数token');
        }

        // 解析token并初始化会话
        try {
            $appSession = AppSessionHandler::make()->parseToken($token);
        } catch (AppException $e) {
            return AppResponse::sessionInvalid("请先登录（{$e->getMessage()}）");
        }
        $request->appSession = $appSession;

        return $next($request);
    }

}