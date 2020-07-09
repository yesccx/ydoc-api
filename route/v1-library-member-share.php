<?php

/*
 * 文档库用户分享相关 api
 *
 * @Created: 2020-07-04 22:20:25
 * @Author: yesc (yes.ccx@gmail.com)
 */

use think\facade\Route;

Route::group('v1/library/member-share', function () {
    Route::post('doc-share-info', 'docShareInfo');
    Route::post('doc-share-cancel', 'docShareCancel');
    Route::post('doc-share', 'docShare');
    Route::post('library-share-info', 'libraryShareInfo');
    Route::post('library-share-cancel', 'libraryShareCancel');
    Route::post('library-share', 'libraryShare');
})->prefix('v1.library.LibraryMemberShare/')->middleware('AppSessionMiddleware');

return [];