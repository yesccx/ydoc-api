<?php

/*
 * 文档库日志 Controller
 *
 * @Created: 2020-06-22 19:39:55
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\controller\v1\library;

use app\extend\common\AppPagination;
use app\extend\common\AppQuery;
use app\kernel\base\AppBaseController;
use app\service\library\LibraryLogService;
use app\service\library\LibraryService;

class LibraryLogController extends AppBaseController {

    /**
     * 用户参与的文档库日志列表
     */
    public function memberLibraryLogList(AppPagination $page) {
        $query = AppQuery::make();

        // 获取当前用户参与的文档库列表
        $libraryCollection = LibraryService::getMemberLibraryCollection($this->uid, 'library_id');
        if (empty($libraryCollection)) {
            return $this->responseData($page->toEmpty());
        }

        $query->whereIn('library_id', array_column($libraryCollection->toArray(), 'library_id'));
        $pageList = LibraryLogService::getLibraryLogList($query, $page)->toArray();

        // 追加文档库、用户信息
        if (!empty($pageList['list'])) {
            $pageList['list']->load(['user_info' => function ($squery) {
                $squery->field('id,nickname,account,avatar');
            }, 'library_info' => function ($squery) {
                $squery->field('id,uid,team_id,name,desc,sort,create_time');
            }])->append(['user_info.avatar_url', 'operate_message']);
        }

        return $this->responseData($pageList);
    }

}