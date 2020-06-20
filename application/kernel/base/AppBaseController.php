<?php

/*
 * AppBase Controller
 *
 * @Created: 2020-06-17 22:05:28
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\base;

use app\extend\common\AppRequest;
use app\extend\common\AppResponse;
use app\extend\session\AppSession;
use think\Controller;

abstract class AppBaseController extends Controller {

    /**
     * 会话uid
     *
     * @var int
     */
    public $uid = 0;

    public function __construct() {
        $this->uid = AppSession::make()->getUid();
    }

    /**
     * 响应数据
     *
     * @param mixed $data 数据
     * @return void
     */
    public function responseData($data) {
        return AppResponse::data($data);

    }

    /**
     * 响应成功
     *
     * @param string $msg
     * @return void
     */
    public function responseSuccess(string $msg = 'ok') {
        return AppResponse::success($msg);
    }

    /**
     * 响应失败
     *
     * @param string $msg
     * @return void
     */
    public function responseError(string $msg = 'error') {
        return AppResponse::error($msg);
    }

    /**
     * 响应会话失效
     *
     * @param string $msg
     * @return void
     */
    public function responseSessionInvalid(string $msg = 'session invalid') {
        return AppResponse::sessionInvalid($msg);
    }

    /**
     * 获取请求参数 支持默认值和过滤
     *
     * @param string $key 获取的变量名
     * @param mixed $default 默认值
     * @param string $filter 过滤方法
     * @param string $type 参数类型
     * @return mixed
     */
    public function input($key = '', $default = null, $filter = '', $type = 'post') {
        return AppRequest::input($key, $default, $filter, $type);
    }

    /**
     * 批量获取请求参数
     *
     * @param array $field 参数
     * @param string $type 参数类型 post get
     * @return array
     */
    public function inputMany($fields, $type = 'post') {
        return AppRequest::inputMany($fields, $type);
    }

}