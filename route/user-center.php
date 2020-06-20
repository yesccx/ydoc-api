<?php

use think\facade\Route;

Route::group('v1/user/center', function () {
    Route::post('user-info', 'userInfo');
    Route::post('user-logout', 'userLogout');
})->prefix('v1.user.UserCenter/')->middleware('AppSessionMiddleware');

return [];