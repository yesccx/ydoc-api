<?php

/*
 * 用户配置相关 Controller
 *
 * @Created: 2020-07-21 12:31:20
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\controller\v1\user;

use app\extend\library\LibraryPreferenceHandler;
use app\kernel\base\AppBaseController;
use app\service\user\UserConfigService;

class UserConfigController extends AppBaseController {

    /**
     * 获取用户配置值
     */
    public function configValue() {
        $config = UserConfigService::getUserConfig($this->uid);
        $configParse = LibraryPreferenceHandler::make()->parse($config);

        return $this->responseData(['config' => $config, 'parse' => $configParse]);
    }

    /**
     * 获取用户配置项值
     */
    public function configFieldValue() {
        $field = $this->input('field/s', '');
        if (empty($field)) {
            return $this->responseError('配置项名不能为空');
        }

        $value = UserConfigService::getUserConfigField($this->uid, $field, '');
        return $this->responseData(['value' => $value]);
    }

    /**
     * 用户配置修改
     */
    public function configModify() {
        $config = $this->input('config/a', []);

        $updateRes = UserConfigService::setUserConfig($this->uid, $config);
        if (empty($updateRes)) {
            return $this->responseError('修改失败');
        }

        return $this->responseData('修改成功');
    }

    /**
     * 用户配置项修改
     */
    public function configFieldModify() {
        $field = $this->input('field/s', '');
        $value = $this->input('value/s', '');
        if (empty($field)) {
            return $this->responseError('配置项名不能为空');
        }

        $updateRes = UserConfigService::setUserConfigField($this->uid, $field, $value);
        if (empty($updateRes)) {
            return $this->responseError('修改失败');
        }

        return $this->responseData('修改成功');
    }

    /**
     * 用户配置重置
     */
    public function configReset() {
        $res = UserConfigService::resetUserConfig($this->uid);
        if (empty($res)) {
            return $this->responseError('重置失败');
        }

        return $this->responseData('重置成功');
    }

}