<?php

/*
 * 文档分组相关 api
 *
 * @Created: 2020-06-25 22:55:13
 * @Author: yesc (yes.ccx@gmail.com)
 */

use think\facade\Route;

Route::group('v1/library/doc-group', function () {
    Route::post('create', 'groupCreate');
    Route::post('modify', 'groupModify');
    Route::post('remove', 'groupRemove');
    Route::post('sort', 'groupSort');
    Route::post('info', 'groupInfo');
    Route::post('tree', 'groupTree');
})->prefix('v1.library.LibraryDocGroup/')->middleware('AppSessionMiddleware');

return [];