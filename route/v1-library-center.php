<?php

/*
 * 文档库相关 api
 *
 * @Created: 2020-06-20 14:44:00
 * @Author: yesc (yes.ccx@gmail.com)
 */

use think\facade\Route;

Route::group('v1/library/center', function () {
    Route::post('info', 'libraryInfo');
    Route::post('list', 'libraryList');
    Route::post('collect', 'libraryCollect');
    Route::post('info', 'libraryInfo');
    Route::post('create', 'libraryCreate');
    Route::post('modify', 'libraryModify');
    Route::post('remove', 'libraryRemove');
    Route::post('transfer', 'libraryTransfer');
})->prefix('v1.library.LibraryCenter/')->middleware('AppSessionMiddleware');

return [];