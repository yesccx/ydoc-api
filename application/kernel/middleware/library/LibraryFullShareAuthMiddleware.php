<?php

/*
 * 文档库整个分享相关 Middleware
 * PS: 限制单文档分享时，禁止获取文档库的其它信息
 *
 * @Created: 2020-07-06 14:40:36
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\middleware\library;

use app\extend\common\AppResponse;
use think\Request;

class LibraryFullShareAuthMiddleware {

    public function handle(Request $request, \Closure $next) {
        if (!empty($request->shareLibraryDocId)) {
            return AppResponse::error('没有操作权限');
        }

        return $next($request);
    }

}