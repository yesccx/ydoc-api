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

}