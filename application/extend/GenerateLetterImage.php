<?php

/*
 * generate letter-image
 *
 * @Created: 2020-07-14 20:07:06
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\extend;

use app\traits\common\EntityMake;
use LasseRafn\InitialAvatarGenerator\InitialAvatar;

class GenerateLetterImage {

    use EntityMake;

    // 配色主题
    public static $themeCollection = [
        [
            "b" => "#F5F7FA", "t" => "#C0C4CC",
        ],
        [
            "b" => "#43576b", "t" => "#e5e5e5",
        ],
        [
            "b" => "#F56C6C", "t" => "#ffffff",
        ],
        [
            "b" => "#e4670e", "t" => "#ffffff",
        ],
        [
            "b" => "#ac5ada", "t" => "#ffffff",
        ],
        [
            "b" => "#317ebe", "t" => "#ffffff",
        ],
        [
            "b" => "#7f8c8d", "t" => "#ffffff",
        ],
    ];

    /**
     * 生成简单的图片
     *
     * @param string $content 图片内容（只取第一个字符）
     * @param string $fileName 图片保存路径
     * @param int $size 图片大小
     * @return mixed
     */
    public function simple($content, $fileName, $size = 500) {
        $content = mb_substr($content, 0, 1);
        return $this->run($content, $fileName, $size);
    }

    /**
     * 生成图片
     *
     * @param string $content 图片内容
     * @param string $fileName 图片保存路径
     * @param int $size 图片大小
     * @return mixed
     */
    public function run($content, $fileName, $size = 500) {
        $theme = $this->getRandomTheme();
        $instance = (new InitialAvatar)->autoFont()->size($size)->name($content);
        $instance->background($theme['b'])->color($theme['t']);

        return $instance->generate()->save($fileName);
    }

    /**
     * 获取随机的配色主题
     *
     * @return array
     */
    protected function getRandomTheme() {
        return static::$themeCollection[random_int(0, count(static::$themeCollection) - 1)];
    }
}
