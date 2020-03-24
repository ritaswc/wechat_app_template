<?php

namespace Hejiang\Storage\Drivers;

use Qcloud\Cos\Client as CosClient;
use Hejiang\Storage\Exceptions\StorageException;

class Qcloud extends BaseDriver
{
    public $region;

    public $appId;

    /**
     * Tencent cloud COS Client
     *
     * @var CosClient
     */
    protected $cosClient;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->cosClient = new CosClient(
            [
                'region' => $this->region,
                'credentials' => [
                    'appId' => $this->appId,
                    'secretId' => $this->accessKey,
                    'secretKey' => $this->secretKey,
                ],
            ]
        );
    }

    public function put($localFile, $saveTo)
    {
        $handle = fopen($localFile, 'rb');
        try {
            $saveTo = ltrim($saveTo, '/');
            $res = $this->cosClient->putObject(
                [
                    'Bucket' => $this->bucket,
                    'Key' => $saveTo,
                    'Body' => $handle,
                ]
            );
        } catch (\Exception $ex) {
            throw new StorageException($ex->getMessage());
        }
        // fclose($handle);
        return $res->get('ObjectURL');
    }
}
