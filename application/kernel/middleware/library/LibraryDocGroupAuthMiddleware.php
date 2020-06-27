<?php

/*
 * 文档分组相关操作鉴权 Middleware
 *
 * @Created: 2020-06-22 11:11:32
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\middleware\library;

use app\extend\common\AppQuery;
use app\extend\common\AppRequest;
use app\extend\common\AppResponse;
use app\service\library\LibraryDocGroupService;
use app\traits\common\ParentMiddlewareHandle;
use think\Request;

class LibraryDocGroupAuthMiddleware extends LibraryAuthMiddleware {

    use ParentMiddlewareHandle;

    public function handle(Request $request, \Closure $next) {
        $request = $this->parentHandle($request);

        $libraryDocGroupId = AppRequest::input('library_doc_group_id/d', 0);
        if (empty($libraryDocGroupId)) {
            return AppResponse::error('缺少必要参数文档分组id');
        }

        // 获取文档分组信息
        $docGroupInfo = LibraryDocGroupService::getLibraryDocGroupInfo($libraryDocGroupId, AppQuery::make(['library_id' => $request->libraryId], 'id'));
        if (empty($docGroupInfo)) {
            return AppResponse::error('文档分组不存在');
        }

        $request->libraryDocGroupId = $libraryDocGroupId;

        return $next($request);
    }

}