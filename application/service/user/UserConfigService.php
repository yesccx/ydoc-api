<?php

/*
 * 用户配置相关 Service
 *
 * @Created: 2020-07-21 12:55:44
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\service\user;

use app\extend\library\LibraryPreferenceHandler;
use app\kernel\model\YUserConfigModel;

class UserConfigService {

    /**
     * 获取用户配置参数
     *
     * @param int $uid 用户uid
     * @return array
     */
    public static function getUserConfig($uid) {
        $data = YUserConfigModel::where(['uid' => $uid])->field('config')->find();
        return LibraryPreferenceHandler::handle($data['config']);
    }

    /**
     * 设置用户配置参数
     * // TODO: 修改配置参数时需要效验合法性
     *
     * @param int $uid 用户uid
     * @param array $config 配置参数
     * @return mixed
     */
    public static function setUserConfig($uid, $config) {
        if (!YUserConfigModel::existsOne([ 'uid' => $uid])) {
            return LibraryPreferenceHandler::initUserConfig($uid, $config);
        }
        return YUserConfigModel::update(['config' => $config, 'update_time' => time()], ['uid' => $uid]);
    }

    /**
     * 获取用户某个配置项值
     *
     * @param int $uid 用户uid
     * @param string $field 参数键
     * @param string $default 默认值
     * @return mixed
     */
    public static function getUserConfigField($uid, $field, $default = '') {
        $config = static::getUserConfig($uid);
        return $config[$field] ?? $default;
    }

    /**
     * 设置用户某个配置项值
     *
     * @param int $uid 用户uid
     * @param string $field 参数键
     * @param string $value 参数值
     * @return mixed
     */
    public static function setUserConfigField($uid, $field, $value) {
        $config = static::getUserConfig($uid);
        $config[$field] = $value;
        return static::setUserConfig($uid, $config);
    }

    /**
     * 重置用户配置参数
     *
     * @param int $uid 用户uid
     * @return mixed
     */
    public static function resetUserConfig($uid) {
        if (!YUserConfigModel::existsOne(['uid' => $uid])) {
            return LibraryPreferenceHandler::initUserConfig($uid, []);
        } else {
            return static::setUserConfig($uid, []);
        }
    }

}