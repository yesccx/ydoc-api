<?php

/*
 * 文档库分享相关 api
 *
 * @Created: 2020-07-06 17:59:24
 * @Author: yesc (yes.ccx@gmail.com)
 */

use app\kernel\middleware\library\LibraryShareAuthMiddleware;
use think\facade\Route;

Route::group('v1/library/share', function () {
    Route::post('info', 'shareInfo');
    Route::post('doc-collection', 'shareDocCollection');
    Route::post('doc-group-tree', 'shareDocGroupTree');
    Route::post('doc-info', 'shareDocInfo');
})->prefix('v1.library.LibraryShare/')->middleware(LibraryShareAuthMiddleware::class);

return [];