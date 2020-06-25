<?php

use app\extend\common\AppResponse;
use think\facade\Request;
use think\facade\Route;

// DEBUG: 暂时开启跨域
Route::header('Access-Control-Allow-Origin', Request::header('Origin', ''))
    ->header('Access-Control-Allow-Methods', 'GET,POST,OPTIONS')
    ->header('Access-Control-Allow-Headers', 'token,Content-Type')
    ->header('Access-Control-Allow-Credentials', 'true')
    ->header('Access-Control-Max-Age', 600)
    ->allowCrossDomain();

// 路由 miss
Route::miss(function () {
    return AppResponse::error('not found');
});

return [];