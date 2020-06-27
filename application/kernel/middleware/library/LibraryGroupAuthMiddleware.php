<?php

/*
 * 文档库分组相关操作鉴权 Middleware
 *
 * @Created: 2020-06-22 10:59:54
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\middleware\library;

use app\extend\common\AppQuery;
use app\extend\common\AppRequest;
use app\extend\common\AppResponse;
use app\service\library\LibraryGroupService;
use think\Request;

class LibraryGroupAuthMiddleware {

    public function handle(Request $request, \Closure $next) {
        $libraryGroupId = AppRequest::input('library_group_id/d', 0);
        if (empty($libraryGroupId)) {
            return AppResponse::error('缺少必要参数分组id');
        }

        // 判断文档库分组是否存在
        $libraryGroupInfo = LibraryGroupService::getLibraryGroupInfo($libraryGroupId, AppQuery::make(['uid' => $request->appSession->uid], 'id'));
        if (empty($libraryGroupInfo)) {
            return AppResponse::error('文档库分组不存在');
        }

        $request->libraryGroupId = $libraryGroupId;

        return $next($request);
    }

}