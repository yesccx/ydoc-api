<?php

/*
 * 用户相关 api
 *
 * @Created: 2020-06-19 15:36:34
 * @Author: yesc (yes.ccx@gmail.com)
 */

use think\facade\Route;

Route::group('v1/user/center', function () {
    Route::post('user-info', 'userInfo');
    Route::post('user-logout', 'userLogout');
    Route::post('user-nickname-modify', 'userNicknameModify');
    Route::post('user-password-modify', 'userPasswordModify');
})->prefix('v1.user.UserCenter/')->middleware('AppSessionMiddleware');

return [];