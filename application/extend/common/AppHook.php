<?php

/*
 * AppHook
 * PS: 封装自原生的Hook，防止hook后抛出异常影响正常结果
 *
 * @Created: 2020-06-20 14:13:46
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\extend\common;

use think\facade\Hook;

class AppHook {

    /**
     * 监听标签的行为
     *
     * @param string $tag    标签名称
     * @param mixed $params 传入参数
     * @param bool $once   只获取一个有效返回值
     * @param bool $catchException 是否捕捉异常
     * @return mixed
     * @throws Exception
     */
    public static function listen($tag, $params = null, $once = false, $catchException = true) {
        try {
            $res = Hook::listen($tag, $params, $once);
        } catch (\Exception $e) {
            if ($catchException) {
                throw $e;
            }
        }

        return $res;
    }

}
