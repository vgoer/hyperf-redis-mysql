<?php

declare(strict_types=1);
/**
 * This file is part of goer.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  3088760685@qq.com
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Utils;

use OSS\Core\OssException;
use OSS\OssClient;

class AliyunOss
{
    private $ossClient;

    private $endpoint;

    public function __construct($accessKeyId, $accessKeySecret, $endpoint)
    {
        try {
            $this->ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint, false);
            $this->endpoint = $endpoint;
            return true;
        } catch (OssException $e) {
            printf(__FUNCTION__ . "creating OssClient instance: FAILED\n");
            printf($e->getMessage() . "\n");
            return false;
        }
    }

    // 上传文件
    public function upLoadFile($bucket, $object, $content, $options = null)
    {
        try {
            $this->ossClient->putObject($bucket, $object, $content, $options);
            return 'https://' . $bucket . '.' . $this->endpoint . '/' . $object;
        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return false;
        }
    }

    // 读取文件内容
    public function downLoadFile($bucket, $object)
    {
        try {
            return $this->ossClient->getObject($bucket, $object);
        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
    }

    // 删除文件
    public function removeFile($bucket, $object)
    {
        $object = str_replace('https://' . $bucket . '.' . $this->endpoint . '/', '', $object);
        try {
            $this->ossClient->deleteObject($bucket, $object);
            return true;
        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return false;
        }
    }
}
