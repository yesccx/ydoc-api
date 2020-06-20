<?php

/*
 * AppException Handler
 *
 * @Created: 2020-06-18 15:04:04
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\exception\handler;

use app\exception\AppException;
use app\extend\common\AppResponse;
use think\exception\Handle;
use think\facade\Log;

class AppExceptionHandler extends Handle {

    public function render($e) {
        $isDebug = config('app.app_debug');

        // 如果为自定义异常，则直接响应
        if ($e instanceof AppException) {
            return AppResponse::error($e->getMessage());
        }

        // 开发模式下，直接报异常
        if ($isDebug || $isDebug == '1') {
            return parent::render($e);
        } else {
            Log::write($e->__toString(), 'error');
        }

        return AppResponse::error('内部错误');
    }

}
