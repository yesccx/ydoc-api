<?php

/*
 * 文档库分享相关 Middleware
 *
 * @Created: 2020-07-06 14:40:36
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\middleware\library;

use app\constants\common\AppResponseCode;
use app\constants\model\YLibraryShareCode;
use app\extend\common\AppRequest;
use app\extend\common\AppResponse;
use app\service\library\LibraryShareService;
use app\service\UserService;
use think\Request;

class LibraryShareAuthMiddleware {

    public function handle(Request $request, \Closure $next) {
        $shareCode = AppRequest::input('share_code/s', '');
        $shareAccessPassword = AppRequest::input('share_access_password/s', '');
        if (empty($shareCode)) {
            return AppResponse::error('缺少必要参数分享码');
        }

        // 获取分享信息
        $shareInfo = LibraryShareService::getLibraryShareByCode(
            $shareCode, 'id,library_id,uid,doc_id,share_name,share_desc,expire_time,access_password,status,is_protected,create_time,share_code,custom_content'
        );
        if (empty($shareInfo)) {
            return AppResponse::error('分享内容不存在或已被删除');
        } else if ($shareInfo['status'] != YLibraryShareCode::STATUS__ENABLED) {
            return AppResponse::error('分享已被禁用或正在审核中');
        } else if (!empty($shareInfo['expire_time']) && $shareInfo['expire_time'] < time()) {
            return AppResponse::error('分享已失效');
        } else if ($shareInfo['is_protected'] == YLibraryShareCode::IS_PROTECTED__YES) {
            // 如果仅密码错误时，响应分享人信息
            $resShareInfo = $shareInfo->hidden(['access_password', 'status'])->toArray();
            $resShareInfo['user_info'] = UserService::getUserInfo($resShareInfo['uid'], 'id,avatar,nickname')->append(['avatar_url']);

            if (empty($shareAccessPassword)) {
                return AppResponse::handleResponse(AppResponseCode::LIBRARY_SHARE_PROTECTED, '查看分享内容需要密码', $resShareInfo);
            } else if ($shareInfo['access_password'] != $shareAccessPassword) {
                return AppResponse::handleResponse(AppResponseCode::LIBRARY_SHARE_PROTECTED, '访问密码错误', $resShareInfo);
            }
        }

        $request->shareCustomContent = (array) $shareInfo['custom_content'];
        $request->shareId = $shareInfo['id'];
        $request->shareLibraryId = $shareInfo['library_id'];
        $request->shareLibraryDocId = $shareInfo['doc_id'];

        return $next($request);
    }

}