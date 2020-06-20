<?php

/*
 * make对象
 *
 * @Created: 2020-06-17 22:06:58
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\traits\common;

trait EntityMake {

    /**
     * 创建对象
     *
     * @return static
     */
    public static function make() {
        return new static;
    }

}