<?php

/*
 * 用户消息相关 api
 *
 * @Created: 2020-07-15 22:09:10
 * @Author: yesc (yes.ccx@gmail.com)
 */

use think\facade\Route;

Route::group('v1/user/message', function () {
    Route::post('list', 'messageList');
    Route::post('info', 'messageInfo');
    Route::post('remove', 'messageRemove');
    Route::post('read-all', 'messageReadAll');
    Route::post('read', 'messageRead');
    Route::post('unread', 'messageUnread');
    Route::post('count-all', 'messageCountAll');
    Route::post('count', 'messageCount');
})->prefix('v1.user.UserMessage/')->middleware('AppSessionMiddleware');

return [];