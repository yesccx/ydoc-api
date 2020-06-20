<?php

use think\facade\Route;

Route::group('v1/guest/user', function () {
    Route::post('account-login', 'accountLogin');
    Route::post('account-register', 'accountRegister');
})->prefix('v1.guest.GuestUser/');

return [];