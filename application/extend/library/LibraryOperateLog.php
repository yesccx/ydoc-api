<?php

/*
 * 文档库相关操作日志
 *
 * @Created: 2020-07-13 21:57:05
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\extend\library;

use app\exception\AppException;
use app\extend\session\AppSession;
use app\kernel\model\YLibraryLogModel;

class LibraryOperateLog {

    /**
     * 文档库id
     *
     * @var int
     */
    protected $libraryId = 0;

    /**
     * 用户uid
     *
     * @var int
     */
    protected $uid = 0;

    /**
     * 创建对象
     *
     * @param int $libraryId 文档库id
     * @param int $uid 用户uid
     * @return static
     */
    public static function make($libraryId, $uid = 0) {
        return (new static )->setContext($libraryId, $uid);
    }

    /**
     * 设置上下文（文档库id、用户uid）
     *
     * @param int $libraryId 文档库id
     * @param int $uid 用户uid
     * @return $this
     * @throws AppException
     */
    public function setContext($libraryId, $uid = 0) {
        $this->libraryId = $libraryId;
        $this->uid = $uid ?: AppSession::make()->getUid();

        return $this;
    }

    /**
     * 写操作日志
     *
     * @param string $type 操作类型
     * @param string|bool $message 操作日志说明
     * @param array $params 相关参数
     * @return $this
     * @throws AppException
     */
    public function write($type, $message = '', $params = []) {
        YLibraryLogModel::create([
            'library_id'     => $this->libraryId,
            'uid'            => $this->uid,
            'operate_type'   => $type,
            'operate_params' => $params,
            'remark'         => $message,
            'create_time'    => time(),
        ]);
    }

    /**
     * 快捷写操作日志
     *
     * @param int $libraryId 文档为id
     * @param string $type 操作类型
     * @param string|bool $message 操作日志说明
     * @param array $params 相关参数
     * @return $this
     * @throws AppException
     */
    public static function record($libraryId, $type, $message = '', $params = []) {
        return static::make($libraryId)->write($type, $message, $params);
    }

}