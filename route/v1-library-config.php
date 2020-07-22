<?php

/*
 * 文档库配置相关 api
 *
 * @Created: 2020-07-21 23:02:10
 * @Author: yesc (yes.ccx@gmail.com)
 */

use think\facade\Route;

Route::group('v1/library/config', function () {
    Route::post('value', 'libraryConfigValue');
    Route::post('field-value', 'libraryConfigFieldValue');
    Route::post('modify', 'libraryConfigModify');
    Route::post('field-modify', 'libraryConfigFieldModify');
    Route::post('member-value', 'libraryConfigMemberValue');
    Route::post('member-field-value', 'libraryConfigMemberFieldValue');
    Route::post('member-modify', 'libraryConfigMemberModify');
    Route::post('member-field-modify', 'libraryConfigMemberFieldModify');
})->prefix('v1.library.LibraryConfig/')->middleware('AppSessionMiddleware');

return [];