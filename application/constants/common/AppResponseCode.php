<?php

/*
 * App响应码 Constant
 *
 * @Created: 2020-06-18 14:40:49
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\constants\common;

use app\constants\extend\BaseCode;

class AppResponseCode extends BaseCode {

    /**
     * response success
     */
    const SUCCESS = 1000;

    /**
     * response error
     */
    const ERROR = 1100;

    /**
     * response session invalid
     */
    const SESSION_INVALID = 1110;

}