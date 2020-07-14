<?php

/*
 * 文档库日志相关 api
 *
 * @Created: 2020-06-22 19:41:20
 * @Author: yesc (yes.ccx@gmail.com)
 */

use think\facade\Route;

Route::group('v1/library/log', function () {
    Route::post('member-library-log-list', 'memberLibraryLogList');
})->prefix('v1.library.LibraryLog/')->middleware('AppSessionMiddleware');

return [];