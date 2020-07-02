<?php

/*
 * 文档模板相关 api
 *
 * @Created: 2020-06-28 12:42:38
 * @Author: yesc (yes.ccx@gmail.com)
 */

use think\facade\Route;

Route::group('v1/library/doc-template', function () {
    Route::post('collection', 'docTemplateCollection');
    Route::post('info', 'docTemplateInfo');
    Route::post('create', 'docTemplateCreate');
    Route::post('modify', 'docTemplateModify');
    Route::post('remove', 'docTemplateRemove');
})->prefix('v1.library.LibraryDocTemplate/')->middleware('AppSessionMiddleware');

return [];