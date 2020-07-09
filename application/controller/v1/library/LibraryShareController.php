<?php

/*
 * 文档库分享相关 Controller
 *
 * @Created: 2020-07-06 12:25:44
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\controller\v1\library;

use app\extend\library\LibraryDocGroupTree;
use app\kernel\base\AppBaseController;
use app\service\library\LibraryDocGroupService;
use app\service\library\LibraryDocService;
use app\service\library\LibraryShareService;
use app\service\UserService;

class LibraryShareController extends AppBaseController {

    /**
     * 分享信息
     */
    public function shareInfo() {
        $shareId = $this->request->shareId;
        $shareInfo = LibraryShareService::getLibraryShareInfo(
            $shareId, 'id,library_id,uid,doc_id,share_name,share_desc,expire_time,is_protected,create_time,share_code'
        );

        // 分享人信息
        $shareInfo['user_info'] = UserService::getUserInfo($shareInfo['uid'], 'id,avatar,nickname')->append(['avatar_url']);

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

        $collection = LibraryDocService::getLibraryDocCollection($libraryId, 'id,library_id,group_id,title,sort,update_time');

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

        $docInfo = LibraryDocService::getLibraryDocInfo($docId, 'id,library_id,group_id,title,content,sort,update_time');
        if (empty($docInfo)) {
            return $this->responseError('文档不存在');
        } else if ($docInfo['library_id'] != $libraryId) {
            return $this->responseError('文档不存在');
        }

        return $this->responseData($docInfo);
    }

}