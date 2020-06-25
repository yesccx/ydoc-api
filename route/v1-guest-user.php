<?php

/*
 * 游客用户相关 api
 *
 * @Created: 2020-06-18 22:15:13
 * @Author: yesc (yes.ccx@gmail.com)
 */

use think\facade\Route;

Route::group('v1/guest/user', function () {
    Route::post('account-login', 'accountLogin');
    Route::post('account-register', 'accountRegister');
})->prefix('v1.guest.GuestUser/');

return [];