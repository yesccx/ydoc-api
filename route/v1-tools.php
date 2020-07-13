<?php

/*
 * 公共工具相关 api
 *
 * @Created: 2020-06-24 19:02:48
 * @Author: yesc (yes.ccx@gmail.com)
 */

use think\facade\Route;

Route::group('v1/tools', function () {
    Route::post('member/collection', 'memberCollection');
    Route::post('image/upload', 'imageUpload');
})->prefix('v1.Tools/')->middleware('AppSessionMiddleware');

return [];