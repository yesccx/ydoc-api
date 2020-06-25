<?php

/*
 * App Exception
 *
 * @Created: 2020-06-18 15:05:13
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\exception;

class AppException extends \Exception {

    private $data = [];

    public function __construct($message, $code = 0, $data = []) {
        parent::__construct($message, $code);
        $this->data = $data;
    }

    public function getData() {
        return $this->data;
    }

    /**
     * 再次抛出异常
     *
     * @param string $prefix 错误内容前缀
     * @throws $this
     */
    public function throwAgain($prefix = '') {
        $prefix = $prefix ? $prefix . '，' : '';
        throw new static($prefix . $this->message);
    }

}