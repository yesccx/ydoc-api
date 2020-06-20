<?php

// 引入环境变量文件
include 'env.php';

/**
 * 获取毫秒时间戳
 *
 * @return int
 */
function milliTimestamp() {
    list($t1, $t2) = explode(' ', microtime());
    return (float) sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
}

/**
 * 生成指定长度的随机字符串
 *
 * @param int $length 结果长度
 * @param string $char 字符集
 * @return string|boolean
 */
function randomStr($length = 32, $char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
    if (!is_int($length) || $length < 0) {
        return '';
    }

    $string = '';
    for ($i = $length; $i > 0; $i--) {
        $string .= $char[mt_rand(0, strlen($char) - 1)];
    }

    return $string;
}

/**
 * 生成指定长度的数字型随机字符串
 *
 * @param int $length 结果长度
 * @return string
 */
function randomNumStr($length) {
    return randomStr($length, '0123456789');
}

/**
 * 写 Debug 调试日志文件
 *
 * @param array|string $content 日志内容
 * @param array|string $content 日志内容
 * @return boolean true为成功 false为失败
 */
function traceLog($content, $level = 'debug') {
    // 准备必要参数
    $uid = 0;
    if (is_array($content)) {
        $content = json_encode($content);
    } else if (is_object($content)) {
        $content = json_encode((array) $content);
    }

    // 定义存放目录，按日志级别分类
    $path = env('runtime_path') . 'trace' . DIRECTORY_SEPARATOR;
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }

    // 调用位置信息
    $debugInfo = debug_backtrace();
    if (isset($debugInfo[0]['line']) && isset($debugInfo[0]['file'])) {
        $content = $debugInfo[0]['file'] . ': ' . $debugInfo[0]['line'] . PHP_EOL . '> ' . $content;
    }
    // 拼接内容，前面加时间及基本信息，后面加换行
    $logInfo = '[' . date('Y-m-d H:i:s') . ' $UID:' . $uid . '] ';
    $content = PHP_EOL . $logInfo . PHP_EOL . $content . PHP_EOL;

    // 输出内容
    file_put_contents($path . "{$level}.log", $content, FILE_APPEND);

    return true;
}