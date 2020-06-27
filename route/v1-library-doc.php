<?php

/*
 * 文档相关 api
 *
 * @Created: 2020-06-25 23:18:33
 * @Author: yesc (yes.ccx@gmail.com)
 */

use think\facade\Route;

Route::group('v1/library/doc', function () {
    Route::post('collection', 'libraryDocCollection');
    Route::post('info', 'libraryDocInfo');
    Route::post('create', 'libraryDocCreate');
    Route::post('modify', 'libraryDocModify');
    Route::post('remove', 'libraryDocRemove');
    Route::post('sort', 'libraryDocSort');
})->prefix('v1.library.LibraryDoc/')->middleware('AppSessionMiddleware');

return [];