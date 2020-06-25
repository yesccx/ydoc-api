<?php

/*
 * 文档库分组相关 api
 *
 * @Created: 2020-06-20 17:02:52
 * @Author: yesc (yes.ccx@gmail.com)
 */

use think\facade\Route;

Route::group('v1/library/group', function () {
    Route::post('collect', 'groupCollect');
    Route::post('info', 'groupInfo');
    Route::post('create', 'groupCreate');
    Route::post('remove', 'groupRemove');
    Route::post('modify', 'groupModify');
    Route::post('sort', 'groupSort');
})->prefix('v1.library.LibraryGroup/')->middleware('AppSessionMiddleware');

return [];