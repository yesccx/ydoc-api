<?php

/*
 * 文档库文档模板相关 Controller
 *
 * @Created: 2020-06-28 12:41:50
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\controller\v1\library;

use app\entity\model\YLibraryDocTemplateEntity;
use app\kernel\base\AppBaseController;
use app\logic\libraryDocTemplate\LibraryDocTemplateCreateLogic;
use app\logic\libraryDocTemplate\LibraryDocTemplateModifyLogic;
use app\logic\libraryDocTemplate\LibraryDocTemplateRemoveLogic;
use app\service\library\LibraryDocTemplateService;
use think\Db;

class LibraryDocTemplateController extends AppBaseController {

    protected $middleware = [
        \app\kernel\middleware\library\LibraryDocTemplateAuthMiddleware::class => [ // 文档模板操作鉴权
            'only' => [
                'docTemplateModify', 'docTemplateRemove', 'docTemplateInfo',
            ],
        ],
    ];

    /**
     * 文档模板集合
     */
    public function docTemplateCollection() {
        $collection = LibraryDocTemplateService::getLibraryDocTemplateCollection(
            $this->uid, 'id,uid,name,introduction,create_time,update_time,content,editor'
        );
        return $this->responseData($collection);
    }

    /**
     * 文档模板创建
     */
    public function docTemplateCreate() {
        $docTempalteEntity = YLibraryDocTemplateEntity::inputMake([
            'name/s' => '', 'introduction/s' => '', 'content/s' => '', 'editor/s' => '',
        ]);
        if (empty($docTempalteEntity->name)) {
            return $this->responseError('请给模板定义一个名称');
        }

        $docTempalteEntity->uid = $this->uid;

        $docTemplateCreate = LibraryDocTemplateCreateLogic::make();
        Db::transaction(function () use ($docTemplateCreate, $docTempalteEntity) {
            $docTemplateCreate->useLibraryDocTemplate($docTempalteEntity)->create();
        });

        return $this->responseData($docTemplateCreate->libraryDocTemplateEntity->toArray());

    }

    /**
     * 文档模板修改
     */
    public function docTemplateModify() {
        $docTempalteEntity = YLibraryDocTemplateEntity::inputMake([
            'name/s' => '', 'introduction/s' => '', 'content/s' => '', 'editor/s' => '',
        ]);
        if (empty($docTempalteEntity->name)) {
            return $this->responseError('请给模板定义一个名称');
        }
        $docTempalteEntity->id = $this->request->libraryDocTemplateId;

        $docTemplateModify = LibraryDocTemplateModifyLogic::make();
        Db::transaction(function () use ($docTemplateModify, $docTempalteEntity) {
            $docTemplateModify->useLibraryDocTemplate($docTempalteEntity)->modify();
        });

        return $this->responseData($docTemplateModify->libraryDocTemplateEntity->toArray());
    }

    /**
     * 文档模板信息
     */
    public function docTemplateInfo() {
        $docTemplateId = $this->request->libraryDocTemplateId;
        $docTemplateInfo = LibraryDocTemplateService::getLibraryDocTemplateInfo(
            $docTemplateId, 'id,uid,name,introduction,content,create_time,update_time,editor'
        );

        return $this->responseData($docTemplateInfo);
    }

    /**
     * 文档模板删除
     */
    public function docTemplateRemove() {
        $libraryDocTemplateId = $this->request->libraryDocTemplateId;

        $docTemplateRemove = LibraryDocTemplateRemoveLogic::make();
        Db::transaction(function () use ($docTemplateRemove, $libraryDocTemplateId) {
            $docTemplateRemove->useLibraryDocTemplate($libraryDocTemplateId)->remove();
        });

        return $this->responseData('删除成功');
    }

}