<?php

/*
 * 七牛云管理相关
 *
 * @Created: 2020-07-13 14:54:55
 * @Author: yesc (yes.ccx@gmail.com)
 */

namespace app\extend;

use app\exception\AppException;
use app\traits\common\EntityMake;
use Qiniu\Auth as QiniuAuth;
use Qiniu\Storage\UploadManager as QiniuUploadManager;
use think\Exception;

class QiniuManager {

    use EntityMake;

    /**
     * Qiniu access_key
     *
     * @var string
     */
    protected $accessKey;

    /**
     * Qiniu secret_key
     *
     * @var string
     */
    protected $secretKey;

    /**
     * Qiniu bucket
     *
     * @var string
     */
    protected $bucket;

    /**
     * Qiniu bucket_url
     *
     * @var string
     */
    protected $bucketUrl;

    /**
     * 可用文件后缀
     *
     * @var array
     */
    protected $fileAccepts = [];

    public function __construct() {
        $this->accessKey = config('qiniu.access_key');
        $this->secretKey = config('qiniu.secret_key');
        $this->bucket = config('qiniu.bucket');
        $this->bucketUrl = config('qiniu.bucket_url');
    }

    /**
     * 单文件上传
     *
     * @param string $saveFileName 保存后的文件名
     * @return array 文件信息
     * @throws Exception
     * @throws \Exception
     */
    public function upload($saveFileName = '') {
        $token = $this->generateBucketToken();

        // 解析文件信息并判断文件格式
        if (empty($files = $_FILES)) {
            throw new AppException('没有上传的文件');
        }
        $file = $this->parseFileInfo(array_values($files)[0]);
        $saveFileName = $saveFileName ?: (hash_file('sha1', $file['tmp_name']) . time());

        // 上传文件
        $uploadManager = new QiniuUploadManager();
        list($res, $error) = $uploadManager->putFile($token, $saveFileName, $file['tmp_name']);
        if ($error !== null) {
            throw new AppException('上传失败');
        }

        return [
            'name' => $saveFileName,
            'key'  => $res['key'],
            'url'  => $this->buildFileUrl($res['key']),
        ];
    }

    /**
     * 解析文件信息
     *
     * @param mixed $file
     * @return array 文件信息
     * @throws AppException
     */
    protected function parseFileInfo($file) {
        // 获取文件信息
        $fileInfo = explode('.', $file['name']);
        if (count($fileInfo) <= 1) {
            throw new AppException('文件格式不正确');
        }

        // 判断文件后缀
        $fileExtension = array_pop($fileInfo);
        if (!empty($this->fileAccepts) && !in_array($fileExtension, $this->fileAccepts)) {
            throw new AppException('文件格式不正确');
        }

        $file['info'] = $fileInfo;

        return $file;
    }

    /**
     * 限制仅图片
     *
     * @return $this
     */
    public function onlyImage() {
        $this->fileAccepts = ['png', 'jpg', 'jpeg', 'gif'];
        return $this;
    }

    /**
     * 构建bucket token
     *
     * @param $bucket 存储桶名称
     * @return string token
     * @throws AppException
     */
    protected function generateBucketToken($bucket = '') {
        $bucket = $bucket ?: $this->bucket;
        $accessKey = $this->accessKey;
        $secretKey = $this->secretKey;

        $token = (new QiniuAuth($accessKey, $secretKey))->uploadToken($bucket);
        if (empty($token)) {
            throw new AppException('request token fail');
        }
        return $token;
    }

    /**
     * 根据文件key获取文件url
     *
     * @param string $fileKey 文件key
     * @return string
     */
    protected function buildFileUrl($fileKey) {
        return $this->bucketUrl . $fileKey;
    }

}