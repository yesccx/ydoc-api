<?php

/*
 * 文档库配置相关 Service
 *
 * @Created: 2020-07-21 22:08:35
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\service\library;

use app\extend\library\LibraryPreferenceHandler;
use app\kernel\model\YLibraryConfigModel;

class LibraryConfigService {

    /**
     * 获取文档库配置参数
     *
     * @param int $libraryId 文档库id
     * @param int $uid 用户uid
     * @return array
     */
    public static function getLibraryConfig($libraryId, $uid = 0) {
        $data = YLibraryConfigModel::where(['library_id' => $libraryId, 'uid' => $uid])->field('config')->find();
        return LibraryPreferenceHandler::handle($data['config']);
    }

    /**
     * 设置用户配置参数
     * // TODO: 修改配置参数时需要效验合法性
     *
     * @param int $libraryId 文档库id
     * @param int $uid 用户uid
     * @param array $config 配置参数
     * @return mixed
     */
    public static function setLibraryConfig($libraryId, $uid, $config) {
        if (!YLibraryConfigModel::existsOne(['library_id' => $libraryId, 'uid' => $uid])) {
            return LibraryPreferenceHandler::initLibraryConfig($libraryId, $uid, $config);
        }
        return YLibraryConfigModel::update(['config' => $config, 'update_time' => time()], ['library_id' => $libraryId, 'uid' => $uid]);
    }

    /**
     * 获取文档库某个配置项值
     *
     * @param int $libraryId 文档库id
     * @param int $uid 用户uid
     * @param string $field 参数键
     * @param string $default 默认值
     * @return mixed
     */
    public static function getLibraryConfigField($libraryId, $uid, $field, $default = '') {
        $config = static::getLibraryConfig($libraryId, $uid);
        return $config[$field] ?? $default;
    }

    /**
     * 设置文档库某个配置项值
     *
     * @param int $libraryId 文档库id
     * @param int $uid 用户uid
     * @param string $field 参数键
     * @param string $value 参数值
     * @return mixed
     */
    public static function setLibraryConfigField($libraryId, $uid, $field, $value) {
        $config = static::getLibraryConfig($libraryId, $uid);
        $config[$field] = $value;
        return static::setLibraryConfig($libraryId, $uid, $config);
    }

}