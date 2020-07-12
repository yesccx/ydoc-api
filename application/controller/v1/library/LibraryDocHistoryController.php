<?php

/*
 * 文档历史记录相关 Controller
 *
 * @Created: 2020-07-03 16:56:17
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\controller\v1\library;

use app\extend\common\AppPagination;
use app\extend\common\AppQuery;
use app\kernel\base\AppBaseController;
use app\service\library\LibraryDocHistoryService;

class LibraryDocHistoryController extends AppBaseController {

    protected $middleware = [
        \app\kernel\middleware\library\LibraryDocAuthMiddleware::class        => [ // 文档操作鉴权
            'only' => [
                'libraryDocHistoryList',
            ],
        ],
        \app\kernel\middleware\library\LibraryDocHistoryAuthMiddleware::class => [ // 文档历史操作鉴权
            'only' => [
                'libraryDocHistoryInfo',
            ],
        ],

    ];

    /**
     * 文档历史列表
     */
    public function libraryDocHistoryList(AppPagination $pagination) {
        $libraryDocId = $this->request->libraryDocId;

        // 获取列表数据
        $query = AppQuery::make()->field('id,title,library_id,uid,create_time,update_time,doc_id')->order('id', 'desc');
        $pageList = LibraryDocHistoryService::getLibraryDocHistoryList($libraryDocId, $query, $pagination)->toArray();

        // 追加文档库信息
        if (!empty($pageList['list'])) {
            $pageList['list']->load(['user_info' => function ($squery) {
                $squery->field('id,nickname,avatar');
            }])->append(['user_info.avatar_url']);
        }

        return $this->responseData($pageList);
    }

    /**
     * 文档历史项信息
     */
    public function libraryDocHistoryInfo() {
        $docHistoryId = $this->request->docHistoryId;
        $docHistoryInfo = LibraryDocHistoryService::getLibraryDocHistoryInfo(
            $docHistoryId, 'id,library_id,doc_id,title,content,group_id'
        );

        return $this->responseData($docHistoryInfo);
    }

}