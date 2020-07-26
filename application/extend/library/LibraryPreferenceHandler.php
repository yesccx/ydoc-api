<?php

/*
 * 文档库偏好设置参数相关处理
 *
 * @Created: 2020-07-21 22:05:40
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\extend\library;

use app\constants\common\LibraryEditorCode;
use app\constants\module\LibraryPreferenceCode;
use app\extend\session\AppSession;
use app\kernel\model\YLibraryConfigModel;
use app\kernel\model\YLibraryDocTemplateModel;
use app\kernel\model\YUserConfigModel;
use app\traits\common\EntityMake;

class LibraryPreferenceHandler {

    use EntityMake;

    // 默认配置参数
    public static $defaultConfig = [
        LibraryPreferenceCode::LIBRARY_DEFAULT_STYLE        => 'full',
        LibraryPreferenceCode::LIBRARY_DOC_DEFAULT_TEMPLATE => 0,
        LibraryPreferenceCode::LIBRARY_DOC_DEFAULT_EDITOR   => LibraryEditorCode::EDITOR_DEFAULT,
        LibraryPreferenceCode::USE_PREFERENCE               => 0,
    ];

    /**
     * 解析配置参数值的函数
     *
     * @param array $config
     * @return array
     */
    public function parse($config) {
        $configContent = [];
        foreach ($config as $key => $value) {
            $handleCall = "parseHandle__{$key}";
            $configContent[$key] = $this->$handleCall($key, $value);
        }

        return $configContent;
    }

    // 解析函数 library_doc_default_template
    protected function parseHandle__library_doc_default_template($configKey, $configValue) {
        // FIMXE: 获取文档库默认使用模板信息时，可以不做uid限定，可能会在成员间共享（实现方式有待优化）
        $uid = AppSession::make()->getUid();
        return YLibraryDocTemplateModel::where(['id' => $configValue, 'uid' => $uid])->value('name', '');
    }

    // 解析函数 library_doc_default_editor
    protected function parseHandle__library_doc_default_editor($configKey, $configValue) {
        $map = [
            LibraryEditorCode::EDITOR_HTML     => '富文本编辑器',
            LibraryEditorCode::EDITOR_MARKDOWN => 'Markdown编辑器',
            LibraryEditorCode::EDITOR_TEXT     => '纯文本编辑器',
        ];
        return $map[$configValue] ?? '';
    }

    // 解析函数 library_default_style
    protected function parseHandle__library_default_style($configKey, $configValue) {
        $map = ['full' => '超宽视图', 'medium' => '标宽视图'];
        return $map[$configValue] ?? '';
    }

    // 解析函数 use_preference_settings
    protected function parseHandle__use_preference_settings($configKey, $configValue) {
        $map = ['1' => '开启', '0' => '未开启'];
        return $map[$configValue] ?? '';
    }

    /**
     * 处理配置参数
     *
     * @param array $config 原配置参数
     * @return arary 处理后的配置参数
     */
    public static function handle($config) {
        $resConfig = [];

        foreach (static::$defaultConfig as $key => $defaultValue) {
            if (!isset($config[$key])) {
                $resConfig[$key] = $defaultValue;
            } else {
                $resConfig[$key] = $config[$key];
            }
        }

        return $resConfig;
    }

    /**
     * 初始化文档库配置参数
     *
     * @param int $libraryId 文档库id
     * @param int $uid 用户uid
     * @param array $config 初始配置参数
     * @return mixed
     */
    public static function initLibraryConfig($libraryId, $uid = 0, $config = []) {
        $config = static::handle($config);
        return YLibraryConfigModel::create([
            'library_id'  => $libraryId,
            'uid'         => $uid,
            'config'      => $config,
            'create_time' => time(),
        ]);
    }

    /**
     * 初始化用户配置参数
     *
     * @param int $uid 用户uid
     * @param array $config 初始配置参数
     * @return mixed
     */
    public static function initUserConfig($uid, $config = []) {
        $config = static::handle($config);
        return YUserConfigModel::create([
            'uid'         => $uid,
            'config'      => $config,
            'create_time' => time(),
        ]);
    }

}