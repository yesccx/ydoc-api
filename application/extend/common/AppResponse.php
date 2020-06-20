<?php

/*
 * App请求响应处理
 *
 * @Created: 2020-06-18 14:39:17
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\extend\common;

use app\constants\common\AppResponseCode;

class AppResponse {

    /**
     * 响应数据
     *
     * @param array|string|int $data 数据
     * @return void
     */
    public static function data($data) {
        return self::handleResponse(AppResponseCode::SUCCESS, 'ok', $data);
    }

    /**
     * 响应成功
     *
     * @param string $msg
     * @return void
     */
    public static function success(string $msg = 'ok') {
        return self::handleResponse(AppResponseCode::SUCCESS, $msg);
    }

    /**
     * 响应失败
     *
     * @param string $msg
     * @return void
     */
    public static function error(string $msg = 'error') {
        return self::handleResponse(AppResponseCode::ERROR, $msg);
    }

    /**
     * 响应会话失效
     *
     * @param string $msg
     * @return void
     */
    public static function sessionInvalid(string $msg = 'session invalid') {
        return self::handleResponse(AppResponseCode::SESSION_INVALID, $msg);
    }

    /**
     * 处理响应
     *
     * @param int $code 状态码
     * @param string $msg 状态说明
     * @param array|string|int $data 数据
     * @return void
     */
    protected static function handleResponse(int $code, string $msg = '', $data = []) {
        die(json(['code' => $code, 'msg' => $msg, 'data' => $data])->send());
    }

}