<?php

/*
 * 用户密码相关处理
 *
 * @Created: 2020-06-19 12:44:06
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\utils\user;

class UserPasswordHandler {

    /**
     * 用户密码加盐
     * // FIXME: 用户密码加盐方式待优化
     *
     * @param string $password 原密码
     * @param string $salt 盐
     * @return string
     */
    public static function encryption($password, $salt) {
        return md5(md5($password) . $salt);
    }

    /**
     * 构建密码salt（16位）
     * // FIXME: 用户密码盐生成方式待优化
     *
     * @param string $confuse 混淆字符串
     * @param int $length salt长度
     * @return string salt
     */
    public static function generateSalt($confuse = '', $length = 16) {
        $confuse = $confuse ?: milliTimestamp();
        return substr(md5($confuse . rand(1000000000, 9999999999)), 0, $length);
    }

    /**
     * 验证加盐密码是否正确
     *
     * @param string $saltPassword 加过盐的密码
     * @param string $checkPassword 待验证的密码
     * @param string $salt 盐
     * @return boolean
     */
    public static function check($saltPassword, $checkPassword, $salt = '') {
        return self::encryption($checkPassword, $salt) === $saltPassword;
    }

}