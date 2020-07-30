<?php

/*
 * 文档库文档全文搜索相关
 *
 * @Created: 2020-07-27 22:36:38
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\extend\library;

use app\constants\common\FulltextIndexCode;
use app\entity\model\YLibraryDocEntity;
use app\extend\common\AppFulltextIndex;
use app\extend\common\AppQuery;
use app\service\library\LibraryService;

class LibraryDocFulltextIndex extends AppFulltextIndex {

    public static $defaultDatabase = FulltextIndexCode::LIBRARY_DOC;

    /**
     * 添加文档索引
     *
     * @param YLibraryDocEntity $libraryDocEntity
     * @return mixed
     */
    public static function addIndex(YLibraryDocEntity $libraryDocEntity) {
        return static::make()->addIndex([
            'id'             => $libraryDocEntity->id,
            'library_id'     => $libraryDocEntity->library_id,
            'library_doc_id' => $libraryDocEntity->id,
            'title'          => $libraryDocEntity->title,
            'content'        => $libraryDocEntity->content,
        ]);
    }

    /**
     * 删除文档索引
     *
     * @param int $librarydocId 文档id
     * @return mixed
     */
    public static function delIndex($librarydocId) {
        if (empty($librarydocId)) {
            return false;
        }
        $handler = static::make()->getIndex();

        // 删除并刷新索引
        $handler->del($librarydocId);
        $handler->flushIndex();

        return $handler;
    }

    /**
     * 根据文档库删除所有文档索引
     *
     * @param int $libraryId 文档库id
     * @return mixed
     */
    public static function delIndexByLibrary($libraryId) {
        if (empty($libraryId)) {
            return false;
        }
        $handler = static::make()->getIndex();

        // 删除并刷新索引
        $handler->del($libraryId, 'library_id');
        $handler->flushIndex();

        return $handler;
    }

    /**
     * 更新文档索引
     *
     * @param YLibraryDocEntity $libraryDocEntity
     * @return mixed
     */
    public static function updateIndexOne(YLibraryDocEntity $libraryDocEntity) {
        return static::make()->updateIndexOne([
            'id'             => $libraryDocEntity->id,
            'library_id'     => $libraryDocEntity->library_id,
            'library_doc_id' => $libraryDocEntity->id,
            'title'          => $libraryDocEntity->title,
            'content'        => $libraryDocEntity->content,
        ]);
    }

    /**
     * 处理结果数据
     *
     * @param array $data
     * @param bool $appendLibraryInfo 是否追加文档库基本信息
     * @return array
     */
    public static function handleResultData($data, $appendLibraryInfo = false) {
        $data = parent::handleResultData($data);

        // 追加文档库基本信息
        if ($appendLibraryInfo) {
            $docLibraryIdCollection = array_column($data, 'library_id');
            if (empty($docLibraryIdCollection)) {
                return [];
            }
            $docLibraryCollection = LibraryService::getLibraryCollection(AppQuery::make()->field('id,name')->whereIn('id', $docLibraryIdCollection));
            $docLibraryCollection = array_column($docLibraryCollection->toArray(), null, 'id');

            foreach ($data as &$doc) {
                $doc['library_info'] = $docLibraryCollection[$doc['library_id']] ?? [];
            }
        }

        return $data;
    }

}