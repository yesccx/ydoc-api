<?php

/*
 * 用户会话
 *
 * @Created: 2020-06-19 12:52:14
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\extend\session;

/**
 * @method static int getUid() 会话uid
 */
class AppSession {

    /**
     * 会话uid
     *
     * @var integer
     */
    public $uid = 0;

    /**
     * 会话token
     *
     * @var string
     */
    public $token = '';

    /**
     * make
     *
     * @return static
     */
    public static function make() {
        return app(self::class);
    }

    /**
     * 静态获取成员属性
     */
    public static function __callStatic($method, $arguments) {
        if (false !== strpos($method, 'get')) {
            return self::make()->$method();
        }
        return null;
    }

    /**
     * 获取会话信息
     *
     * @return array 会话信息
     */
    public function getSession() {
        return [
            'uid'   => $this->uid,
            'token' => $this->token,
        ];
    }

    /**
     * 设置会话信息
     *
     * @param int $uid
     * @param string $token
     * @return $this
     */
    public function setSession($uid, $token = '') {
        return $this->setUid($uid)->setToken($token);
    }

    /**
     * 获取token
     *
     * @return string
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * 设置token
     *
     * @param string $token
     * @return $this
     */
    public function setToken($token = '') {
        $this->token = $token;
        return $this;
    }

    /**
     * 获取uid
     *
     * @return string
     */
    public function getUid() {
        return $this->uid;
    }

    /**
     * 设置uid
     *
     * @param int $uid
     * @return $this
     */
    public function setUid($uid = 0) {
        $this->uid = $uid;
        return $this;
    }

}