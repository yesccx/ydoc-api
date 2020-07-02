<?php

/*
 * 文档相关操作鉴权 Middleware
 *
 * @Created: 2020-06-22 11:09:18
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\middleware\library;

use app\extend\common\AppQuery;
use app\extend\common\AppRequest;
use app\extend\common\AppResponse;
use app\service\library\LibraryDocService;
use app\traits\common\ParentMiddlewareHandle;
use think\Request;

class LibraryDocAuthMiddleware extends LibraryAuthMiddleware {

    use ParentMiddlewareHandle;

    public function handle(Request $request, \Closure $next) {
        $request = $this->parentHandle($request);
        $libraryDocId = AppRequest::input('library_doc_id/d', 0);
        if (empty($libraryDocId)) {
            return AppResponse::error('缺少必要参数文档id');
        }

        // 获取文档信息
        $docInfo = LibraryDocService::getLibraryDocInfo(
            $libraryDocId, AppQuery::make(['library_id' => $request->libraryId], 'id')
        );
        if (empty($docInfo)) {
            return AppResponse::error('文档不存在');
        }

        $request->libraryDocId = $libraryDocId;

        return $next($request);
    }

}