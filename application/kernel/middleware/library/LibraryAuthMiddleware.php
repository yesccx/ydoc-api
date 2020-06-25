<?php

/*
 * 文档库相关操作鉴权 Middleware
 *
 * @Created: 2020-06-22 09:26:40
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\middleware\library;

use app\extend\common\AppRequest;
use app\extend\common\AppResponse;
use app\extend\library\LibraryMemberOperate;
use think\Request;

class LibraryAuthMiddleware {

    public function handle(Request $request, \Closure $next) {
        $libraryId = AppRequest::input('library_id/d', 0);
        if (empty($libraryId)) {
            return AppResponse::error('缺少必要参数文档库id');
        }

        LibraryMemberOperate::make()->init($libraryId, $request->appSession->uid);

        $request->libraryId = $libraryId;

        return $next($request);
    }

}