<?php

/*
 * 文档模板相关操作鉴权 Middleware
 *
 * @Created: 2020-06-30 10:32:50
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\middleware\library;

use app\extend\common\AppQuery;
use app\extend\common\AppRequest;
use app\extend\common\AppResponse;
use app\service\library\LibraryDocTemplateService;
use think\Request;

class LibraryDocTemplateAuthMiddleware {

    public function handle(Request $request, \Closure $next) {
        $libraryDocTemplateId = AppRequest::input('library_doc_template_id/d', 0);
        if (empty($libraryDocTemplateId)) {
            return AppResponse::error('缺少必要参数文档模板id');
        }

        // 获取模板信息
        $docGroupInfo = LibraryDocTemplateService::getLibraryDocTemplateInfo(
            $libraryDocTemplateId, AppQuery::make(['uid' => $request->appSession->uid], 'id')
        );
        if (empty($docGroupInfo)) {
            return AppResponse::error('文档模板不存在');
        }

        $request->libraryDocTemplateId = $libraryDocTemplateId;

        return $next($request);
    }

}