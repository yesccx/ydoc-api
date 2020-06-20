<?php

/*
 * Base Validate
 *
 * @Created: 2020-06-18 22:20:56
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\validate\extend;

use app\exception\AppException;
use think\Validate;

abstract class BaseValidate extends Validate {

    /**
     * 验证数据，失败时抛出异常
     *
     * @param array $data 待验证的数据
     * @param string $scene 验证场景
     * @return bool
     * @throws AppException
     */
    public static function checkOrException($data, $scene = '') {
        $validate = new static;
        $checkRes = $validate->scene($scene)->check($data);
        if (true !== $checkRes) {
            throw new AppException($validate->getError());
        }
        return true;
    }

}