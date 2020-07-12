<?php
/*
 * 文档库管理分享操作鉴权 Middleware
 *
 * @Created: 2020-07-10 09:21:07
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\middleware\library;

use app\extend\common\AppRequest;
use app\extend\common\AppResponse;
use app\service\library\LibraryShareService;
use app\traits\common\ParentMiddlewareHandle;
use think\Request;

class LibraryManagerShareAuthMiddleware extends LibraryAuthMiddleware {

    use ParentMiddlewareHandle;

    public function handle(Request $request, \Closure $next) {
        $request = $this->parentHandle($request);

        $libraryShareId = AppRequest::input('library_share_id/s', '');
        if (empty($libraryShareId)) {
            return AppResponse::error('缺少必要参数分享id');
        }

        // 获取分享信息
        $shareInfo = LibraryShareService::getLibraryShareInfo($libraryShareId, 'id,library_id');
        if (empty($shareInfo)) {
            return AppResponse::error('分享内容不存在');
        } else if ($shareInfo['library_id'] != $request->libraryId) {
            return AppResponse::error('分享内容不存在');
        }

        $request->libraryShareId = $libraryShareId;

        return $next($request);
    }

}