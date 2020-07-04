<?php

/*
 * 文档历史相关 api
 *
 * @Created: 2020-07-03 17:02:21
 * @Author: yesc (yes.ccx@gmail.com)
 */

use think\facade\Route;

Route::group('v1/library/doc-history', function () {
    Route::post('list', 'libraryDocHistoryList');
    Route::post('info', 'libraryDocHistoryInfo');
})->prefix('v1.library.LibraryDocHistory/')->middleware('AppSessionMiddleware');

return [];