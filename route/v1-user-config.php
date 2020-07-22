<?php

/*
 * 用户配置相关 api
 *
 * @Created: 2020-07-21 13:26:50
 * @Author: yesc (yes.ccx@gmail.com)
 */

use think\facade\Route;

Route::group('v1/user/config', function () {
    Route::post('value', 'configValue');
    Route::post('field-value', 'configFieldValue');
    Route::post('modify', 'configModify');
    Route::post('field-modify', 'configFieldModify');
    Route::post('reset', 'configReset');
})->prefix('v1.user.UserConfig/')->middleware('AppSessionMiddleware');

return [];