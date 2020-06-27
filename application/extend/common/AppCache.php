<?php

/*
 * AppCache
 *
 * @Created: 2020-06-19 13:26:37
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\extend\common;

use think\cache\Driver as CacheDriver;
use think\facade\Cache;

/**
 *
 * @method bool has($name) 判断缓存是否存在
 * @method mixed get($name, $default = null) 读取缓存
 * @method boolean set($name, $value, $expire = null) 写入缓存
 * @method boolean rm($name) 删除缓存
 * @method boolean clear() 清除缓存
 */
class AppCache {

    /**
     * 缓存前缀标签名
     *
     * @var string
     */
    protected $tagName = '';

    protected function __construct(string $tagName = '') {
        $this->tagName = $tagName;
    }

    public function __call(string $name, $arguments) {
        /** @var CacheDriver */
        $cache = Cache::tag($this->tagName);
        if (!empty($this->tagName) && isset($arguments[0])) {
            $arguments[0] = $this->handleKey($arguments[0]);
        }

        if ($name === 'clear') {
            return $cache->clear($this->tagName);
        }
        return $cache->{$name}(...$arguments);
    }

    /**
     * 缓存标签
     * PS: 所有缓存操作需要定义标签
     *
     * @param string $tagName
     * @return $this
     */
    public static function tag(string $tagName) {
        return new self($tagName);
    }

    /**
     * 处理key（附加tag前缀）
     *
     * @param string|array $keyCollection
     * @return string|array
     */
    protected function handleKey($keyCollection) {
        $tagName = $this->tagName;
        if (is_array($keyCollection)) {
            return array_map(function ($key) use ($tagName) {
                return "{$tagName}@{$key}";
            }, $keyCollection);
        } else {
            return "{$tagName}@{$keyCollection}";
        }
    }

}