<?php

/*
 * 处理父级中间件
 *
 * @Created: 2020-06-25 23:38:32
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\traits\common;

use think\Request;

trait ParentMiddlewareHandle {

    /**
     * 处理父级中间件
     *
     * @param Request $request
     * @return Request
     */
    public function parentHandle(Request $request) {
        return parent::handle($request, function ($request) {
            return $request;
        });
    }

}