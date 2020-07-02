<?php

/*
 * 文档分组相关 api
 *
 * @Created: 2020-06-25 22:55:13
 * @Author: yesc (yes.ccx@gmail.com)
 */

use think\facade\Route;

Route::group('v1/library/doc-group', function () {
    Route::post('create', 'docGroupCreate');
    Route::post('modify', 'docGroupModify');
    Route::post('remove', 'docGroupRemove');
    Route::post('sort', 'docGroupSort');
    Route::post('info', 'docGroupInfo');
    Route::post('tree', 'docGroupTree');
})->prefix('v1.library.LibraryDocGroup/')->middleware('AppSessionMiddleware');

return [];