<?php

/*
 * 文档库分组相关 api
 *
 * @Created: 2020-06-20 17:02:52
 * @Author: yesc (yes.ccx@gmail.com)
 */

use think\facade\Route;

Route::group('v1/library/group', function () {
    Route::post('collection', 'libraryGroupCollection');
    Route::post('info', 'libraryGroupInfo');
    Route::post('create', 'libraryGroupCreate');
    Route::post('remove', 'libraryGroupRemove');
    Route::post('modify', 'libraryGroupModify');
    Route::post('sort', 'libraryGroupSort');
})->prefix('v1.library.LibraryGroup/')->middleware('AppSessionMiddleware');

return [];