<?php

/*
 * 文档库配置相关 Controller
 *
 * @Created: 2020-07-21 22:07:36
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\controller\v1\library;

use app\constants\common\LibraryOperateCode;
use app\constants\module\LibraryMemberOperateCode;
use app\extend\library\LibraryPreferenceHandler;
use app\extend\library\LibraryMemberOperate;
use app\extend\library\LibraryOperateLog;
use app\kernel\base\AppBaseController;
use app\service\library\LibraryConfigService;

class LibraryConfigController extends AppBaseController {

    protected $middleware = [
        \app\kernel\middleware\library\LibraryAuthMiddleware::class => [ // 文档库操作鉴权
            'only' => [
                'libraryConfigValue', 'libraryConfigFieldValue', 'libraryConfigModify', 'libraryConfigFieldModify',
                'libraryConfigMemberValue', 'libraryConfigMemberFieldValue', 'libraryConfigMemberModify', 'libraryConfigMemberFieldModify',
            ],
        ],
    ];

    /**
     * 获取文档库配置值
     */
    public function libraryConfigValue() {
        $libraryId = $this->request->libraryId;
        $config = LibraryConfigService::getLibraryConfig($libraryId, 0);
        $configParse = LibraryPreferenceHandler::make()->parse($config);

        return $this->responseData(['config' => $config, 'parse' => $configParse]);
    }

    /**
     * 获取文档库配置项值
     */
    public function libraryConfigFieldValue() {
        $libraryId = $this->request->libraryId;
        $field = $this->input('field/s', '');
        if (empty($field)) {
            return $this->responseError('配置项名不能为空');
        }

        $value = LibraryConfigService::getLibraryConfigField($libraryId, 0, $field, '');
        return $this->responseData(['value' => $value]);
    }

    /**
     * 文档库配置修改
     */
    public function libraryConfigModify() {
        LibraryMemberOperate::checkOperate(LibraryMemberOperateCode::LIBRARY_CONFIG__MODIFY);

        $libraryId = $this->request->libraryId;
        $config = $this->input('config/a', []);

        $updateRes = LibraryConfigService::setLibraryConfig($libraryId, 0, $config);
        if (empty($updateRes)) {
            return $this->responseError('修改失败');
        }
        LibraryOperateLog::record($libraryId, LibraryOperateCode::LIBRARY_CONFIG_MODIFY, '更新了偏好设置', $config);

        return $this->responseData('修改成功');
    }

    /**
     * 文档库配置项修改
     */
    public function libraryConfigFieldModify() {
        LibraryMemberOperate::checkOperate(LibraryMemberOperateCode::LIBRARY_CONFIG__MODIFY);

        $libraryId = $this->request->libraryId;
        $field = $this->input('field/s', '');
        $value = $this->input('value/s', '');
        if (empty($field)) {
            return $this->responseError('配置项名不能为空');
        }

        $updateRes = LibraryConfigService::setLibraryConfigField($libraryId, 0, $field, $value);
        if (empty($updateRes)) {
            return $this->responseError('修改失败');
        }
        LibraryOperateLog::record($libraryId, LibraryOperateCode::LIBRARY_CONFIG_MODIFY, '更新偏好设置', ['field' => $field, 'value' => $value]);

        return $this->responseData('修改成功');
    }

    /**
     * 获取文档库成员配置值
     */
    public function libraryConfigMemberValue() {
        $libraryId = $this->request->libraryId;
        $config = LibraryConfigService::getLibraryConfig($libraryId, $this->uid);
        $configParse = LibraryPreferenceHandler::make()->parse($config);

        return $this->responseData(['config' => $config, 'parse' => $configParse]);
    }

    /**
     * 获取文档库成员配置项值
     */
    public function libraryConfigMemberFieldValue() {
        $libraryId = $this->request->libraryId;
        $field = $this->input('field/s', '');
        if (empty($field)) {
            return $this->responseError('配置项名不能为空');
        }

        $value = LibraryConfigService::getLibraryConfigField($libraryId, $this->uid, $field, '');
        return $this->responseData(['value' => $value]);
    }

    /**
     * 文档库成员配置修改
     */
    public function libraryConfigMemberModify() {
        $libraryId = $this->request->libraryId;
        $config = $this->input('config/a', []);

        $updateRes = LibraryConfigService::setLibraryConfig($libraryId, $this->uid, $config);
        if (empty($updateRes)) {
            return $this->responseError('修改失败');
        }

        return $this->responseData('修改成功');
    }

    /**
     * 文档库成员配置项修改
     */
    public function libraryConfigMemberFieldModify() {
        $libraryId = $this->request->libraryId;
        $field = $this->input('field/s', '');
        $value = $this->input('value/s', '');
        if (empty($field)) {
            return $this->responseError('配置项名不能为空');
        }

        $updateRes = LibraryConfigService::setLibraryConfigField($libraryId, $this->uid, $field, $value);
        if (empty($updateRes)) {
            return $this->responseError('修改失败');
        }

        return $this->responseData('修改成功');
    }

}