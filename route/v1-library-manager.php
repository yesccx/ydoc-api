<?php

/*
 * 文档库管理相关 api
 *
 * @Created: 2020-06-23 12:23:08
 * @Author: yesc (yes.ccx@gmail.com)
 */

use think\facade\Route;

Route::group('v1/library/manager', function () {
    Route::post('info', 'libraryManagerInfo');
    Route::post('library-member/library-sort', 'libraryMemberLibrarySort');
    Route::post('library-member/status-modify', 'libraryMemberStatusModify');
    Route::post('library-member/role-modify', 'libraryMemberRoleModify');
    Route::post('library-member/collection', 'libraryMemberCollection');
    Route::post('library-member/invite', 'libraryMemberInvite');
    Route::post('library-member/uninvite', 'libraryMemberUninvite');
    Route::post('share-list', 'libraryShareList');
    Route::post('share-remove', 'libraryShareRemove');
    Route::post('share-status-modify', 'libraryShareStatusModify');
})->prefix('v1.library.LibraryManager/')->middleware('AppSessionMiddleware');

return [];