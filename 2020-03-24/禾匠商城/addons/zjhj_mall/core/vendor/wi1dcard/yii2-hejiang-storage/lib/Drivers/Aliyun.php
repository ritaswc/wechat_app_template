<?php

namespace Hejiang\Storage\Drivers;

use OSS\OssClient;
use OSS\Core\OssException;
use Hejiang\Storage\Exceptions\StorageException;

class Aliyun extends BaseDriver
{
    public $isCName = false;
    
    public $endPoint = '';

    /**
     * Aliyun OSS Client
     *
     * @var OssClient
     */
    protected $ossClient;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->ossClient = new OssClient(
            $this->accessKey,
            $this->secretKey,
            $this->endPoint,
            $this->isCName
        );
    }

    public function put($localFile, $saveTo)
    {
        try {
            $res = $this->ossClient->uploadFile($this->bucket, $saveTo, $localFile);
        } catch (OssException $ex) {
            throw new StorageException($ex->getErrorMessage() ?: $ex->getMessage());
        }
        return $res['oss-request-url'];
    }
}
