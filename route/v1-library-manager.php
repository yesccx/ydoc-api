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
    Route::post('library-member/group-modify', 'libraryMemberGroupModify');
    Route::post('library-member/status-modify', 'libraryMemberStatusModify');
    Route::post('library-member/role-modify', 'libraryMemberRoleModify');
    Route::post('library-member/collect', 'libraryMemberCollect');
    Route::post('library-member/invite', 'libraryMemberInvite');
    Route::post('library-member/uninvite', 'libraryMemberUninvite');
})->prefix('v1.library.LibraryManager/')->middleware('AppSessionMiddleware');

return [];