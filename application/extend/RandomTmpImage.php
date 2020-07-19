<?php

/*
 * 随机生成临时图片
 *
 * @Created: 2020-07-14 17:18:05
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\extend;

use app\extend\GenerateLetterImage;
use app\traits\common\EntityMake;

class RandomTmpImage {

    use EntityMake;

    /**
     * 头像存放目录
     *
     * @var string
     */
    protected $tmpPath = '';

    public function __construct() {
        $this->tmpPath = checkMkdir(ROOT_STATIC_TMP_PATH);
    }

    /**
     * 构建随机图片
     *
     * @param string $key 图片内容
     * @param string $path 保存路径，默认取配置路径
     * @return string 图片外链
     */
    public function generate($key, $path = '') {
        // 随机生成文件名
        $id = randomStr(32);

        // 获取图片文件完整路径
        $fullName = $this->getImageFullName($id, $path);
        checkMkdir(pathinfo($fullName, PATHINFO_DIRNAME));

        // 生成
        GenerateLetterImage::make()->simple($key, $fullName, 500);

        return $this->getImageUrl($id);
    }

    /**
     * Generate random color
     * Stolen from here: https://stackoverflow.com/a/5614583/1103397
     *
     * @return string Random hex color
     */
    public function generateRandomColor() {
        $color = [];
        for ($i = 0; $i < 3; $i++) {
            $color[] = str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
        }

        return implode('', $color);
    }

    /**
     * 获取图片文件完整路径
     *
     * @param string $id 唯一标识（文件名）
     * @param string $path 保存路径，默认取配置路径
     * @return string 带绝对路径的文件名
     */
    protected function getImageFullName($id) {
        $path = $this->tmpPath;
        return "{$path}/{$id}.png";
    }

    /**
     * 获取图片外链
     *
     * @param string $id 唯一标识（文件名）
     * @return string 图片外链
     */
    protected function getImageUrl($id) {
        return APP_ROOT_STATIC_TMP_URL . "/{$id}.png";
    }

}