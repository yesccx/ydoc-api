<?php

/*
 * 基础环境变量
 * PS: 在common.php 文件处引入
 *
 * @Created: 2020-06-18 15:32:02
 * @Author: yesc (yes.ccx@gmail.com)
 */

use think\facade\Env;

// 项目根路径
define('ROOT_PATH', Env::get('root_path'));

// 项目静态资源路径
define('ROOT_STATIC_PATH', Env::get('root_path') . '/public/static');

// 项目静态临时资源路径
define('ROOT_STATIC_TMP_PATH', Env::get('root_path') . '/public/static/tmp');

// 项目应用目录
define('APP_PATH', Env::get('app_path'));

// 应用根url（带协议头）
define('APP_ROOT_URL', Env::get('APP.ROOT_URL', ''));

// 静态临时资源根url
define('APP_ROOT_TMP_STATIC_URL', APP_ROOT_URL . '/tmp');