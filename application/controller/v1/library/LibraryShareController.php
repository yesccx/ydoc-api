<?php

/*
 * 文档库分享相关 Controller
 *
 * @Created: 2020-07-06 12:25:44
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\controller\v1\library;

use app\constants\module\LibraryPreferenceCode;
use app\extend\common\AppPagination;
use app\extend\library\LibraryDocFulltextIndex;
use app\extend\library\LibraryDocGroupTree;
use app\kernel\base\AppBaseController;
use app\service\library\LibraryDocGroupService;
use app\service\library\LibraryDocService;
use app\service\library\LibraryService;
use app\service\library\LibraryShareService;
use app\service\UserService;

class LibraryShareController extends AppBaseController {

    protected $middleware = [
        \app\kernel\middleware\library\LibraryFullShareAuthMiddleware::class => [ // 文档库整个分享时的操作鉴权 PS: 限制单文档分享时，获取文档库的其它信息
            'only' => [
                'shareDocCollection', 'shareDocGroupTree', 'shareFulltextSearch',
            ],
        ],
    ];

    /**
     * 分享信息
     */
    public function shareInfo() {
        $libraryId = $this->request->shareLibraryId;
        $shareId = $this->request->shareId;
        $shareInfo = LibraryShareService::getLibraryShareInfo(
            $shareId, 'id,library_id,uid,doc_id,share_name,share_desc,expire_time,is_protected,create_time,share_code'
        );

        // 分享人信息
        $shareInfo['user_info'] = UserService::getUserInfo($shareInfo['uid'], 'id,avatar,nickname')->append(['avatar_url']);

        // 分享的文档库偏好-视图风格
        $libraryPreference = LibraryService::getLibraryPreference($libraryId);
        $libraryPreferenceStyle = $libraryPreference[LibraryPreferenceCode::LIBRARY_DEFAULT_STYLE];
        $shareInfo['library_style'] = $libraryPreferenceStyle;

        // 累计访问量
        LibraryShareService::incShareAccessCount($shareId);

        return $this->responseData($shareInfo);
    }

    /**
     * 分享的文档库文档集合
     */
    public function shareDocCollection() {
        $libraryId = $this->request->shareLibraryId;

        // 文档分享时，不能查看文档库内容
        $shareLibraryDocId = $this->request->shareLibraryDocId;
        if (!empty($shareLibraryDocId)) {
            return $this->responseError('内容不存在');
        }

        $collection = LibraryDocService::getLibraryDocCollection($libraryId, 'id,library_id,group_id,title,sort,update_time,editor');

        return $this->responseData($collection);
    }

    /**
     * 分享的文档库文档分组树
     */
    public function shareDocGroupTree() {
        $libraryId = $this->request->shareLibraryId;

        // 文档分享时，不能查看文档库内容
        $shareLibraryDocId = $this->request->shareLibraryDocId;
        if (!empty($shareLibraryDocId)) {
            return $this->responseError('内容不存在');
        }

        // 获取文档分组集合，构建分组树
        $collection = LibraryDocGroupService::getLibraryDocGroupCollection($libraryId, 'id,library_id,pid,name,desc,sort');
        $tree = LibraryDocGroupTree::buildTree(0, $collection);

        return $this->responseData($tree);
    }

    /**
     * 分享的文档库分档信息
     */
    public function shareDocInfo() {
        $shareLibraryDocId = $this->request->shareLibraryDocId;
        $libraryId = $this->request->shareLibraryId;
        $docId = $this->input('doc_id/d', $shareLibraryDocId);
        if (empty($docId)) {
            return $this->responseError('缺少必要参数文档id');
        }

        // 文档分享时，仅能查看分享的文档内容
        if (!empty($shareLibraryDocId) && $shareLibraryDocId != $docId) {
            return $this->responseError('内容不存在');
        }

        $docInfo = LibraryDocService::getLibraryDocInfo($docId, 'id,library_id,group_id,title,content,sort,update_time,editor');
        if (empty($docInfo)) {
            return $this->responseError('文档不存在');
        } else if ($docInfo['library_id'] != $libraryId) {
            return $this->responseError('文档不存在');
        }

        return $this->responseData($docInfo);
    }

    /**
     * 分享的文档库全文检索
     */
    public function shareFulltextSearch(AppPagination $pagination) {
        $searchKey = $this->input('search_key/s', '');
        if (empty($searchKey)) {
            return $this->responseError('搜索关键字不能为空');
        }
        $libraryId = $this->request->shareLibraryId;

        $fulltextSearch = LibraryDocFulltextIndex::make()->setLimit($pagination->pageSize, ($pagination->pageNum - 1) * $pagination->pageSize);
        $fulltextData = $fulltextSearch->search("\"{$searchKey}\" AND library_id:{$libraryId}");

        $pageList = AppPagination::make()->setPageData([
            'list'      => LibraryDocFulltextIndex::handleResultData($fulltextData['doc']),
            'total'     => $fulltextData['count'],
            'page_size' => $pagination->pageSize,
            'page_num'  => (int) ceil($fulltextData['count'] / $pagination->pageSize),
        ]);

        return $this->responseData($pageList);
    }

}