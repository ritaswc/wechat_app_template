<?php

namespace Hejiang\Storage\Drivers;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Hejiang\Storage\Exceptions\StorageException;

class Qiniu extends BaseDriver
{
    /**
     * Qiniu auth class
     *
     * @var Auth
     */
    protected $qiniuAuth;

    /**
     * Qiniu upload manager class
     *
     * @var UploadManager
     */
    protected $qiniuUploadManager;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->qiniuAuth = new Auth($this->accessKey, $this->secretKey);
        $this->qiniuUploadManager = new UploadManager();
    }

    public function put($localFile, $saveTo)
    {
        $token = $this->qiniuAuth->uploadToken($this->bucket);
        list($res, $err) = $this->qiniuUploadManager->putFile($token, $saveTo, $localFile);
        if ($err !== null) {
            throw new StorageException($err->message());
        }
        return $res['key'];
    }
}
