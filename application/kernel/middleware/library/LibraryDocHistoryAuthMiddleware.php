<?php

/*
 * 文档历史相关操作鉴权 Middleware
 *
 * @Created: 2020-07-03 20:30:09
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\middleware\library;

use app\extend\common\AppQuery;
use app\extend\common\AppRequest;
use app\extend\common\AppResponse;
use app\service\library\LibraryDocHistoryService;
use app\traits\common\ParentMiddlewareHandle;
use think\Request;

class LibraryDocHistoryAuthMiddleware extends LibraryAuthMiddleware {

    use ParentMiddlewareHandle;

    public function handle(Request $request, \Closure $next) {
        $request = $this->parentHandle($request);
        $docHistoryId = AppRequest::input('doc_history_id/d', 0);
        if (empty($docHistoryId)) {
            return AppResponse::error('缺少必要参数文档历史项id');
        }

        // 获取文档历史项信息
        $docHistoryInfo = LibraryDocHistoryService::getLibraryDocHistoryInfo(
            $docHistoryId, AppQuery::make(['library_id' => $request->libraryId], 'id')
        );
        if (empty($docHistoryInfo)) {
            return AppResponse::error('文档历史项不存在');
        }

        $request->docHistoryId = $docHistoryId;

        return $next($request);
    }

}