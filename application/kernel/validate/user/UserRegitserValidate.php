<?php

/*
 * 用户注册 Validate
 *
 * @Created: 2020-06-19 20:33:47
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\kernel\validate\user;

use app\kernel\model\YUserModel;
use app\kernel\validate\extend\BaseValidate;

class UserRegitserValidate extends BaseValidate {

    protected $rule = [
        'account'  => ['require', 'regex' => '/^[a-zA-z][a-zA-z0-9_]+$/', 'length' => '3,16', 'checkUserExists'],
        'password' => ['require', 'length' => '6,20', 'regex' => '/^[a-zA-z0-9@.-_!#]+$/'],
        'email'    => ['email' => 'email'],
    ];

    protected $message = [
        'account.require'  => '请输入用户名',
        'account.regex'    => '用户名只能由字母、数字组成，且第一个字符必需为字母',
        'account.length'   => '用户名长度为5-16个字符',
        'password.require' => '请输入密码',
        'password.length'  => '密码长度为6-20个字符',
        'password.regex'   => '密码中不能有特殊字符',
        'email.email'      => '邮箱格式不合法',
    ];

    protected $scene = [
        'account' => ['account', 'password', 'email'], // 用户账号方式注册
    ];

    /**
     * 验证用户是否已存在
     *
     * @param string $account 用户登录名
     * @return boolean|string
     */
    protected function checkUserExists($account) {
        $exists = YUserModel::existsOne(['account' => $account]);
        return $exists ? '该账号已被注册' : true;
    }

}