<?php
namespace Qcloud_cos;

class Auth
{

    const AUTH_URL_FORMAT_ERROR = -1;
    const AUTH_SECRET_ID_KEY_ERROR = -2;

    /**
     * 生成多次有效签名函数（用于上传和下载资源，有效期内可重复对不同资源使用）
     * @param  int $expired    过期时间,unix时间戳  
     * @param  string $bucketName 文件所在bucket
     * @return string          签名
     */
    public static function appSign($expired, $bucketName) {

        $appId = Conf::APPID;
        $secretId = Conf::SECRET_ID;
        $secretKey = Conf::SECRET_KEY;
        
        if (empty($secretId) || empty($secretKey) || empty($appId)) {
            return self::AUTH_SECRET_ID_KEY_ERROR;
        }

        return self::appSignBase($appId, $secretId, $secretKey, $expired, null, $bucketName);
    }

     /**
     * 生成单次有效签名函数（用于删除和更新指定fileId资源，使用一次即失效）
     * @param  string $fileId     文件路径，以 /{$appId}/{$bucketName} 开头
     * @param  string $bucketName 文件所在bucket
     * @return string             签名
     */
    public static function appSign_once($path, $bucketName) {

        $appId = Conf::APPID;
        $secretId = Conf::SECRET_ID;
        $secretKey = Conf::SECRET_KEY;

        if (preg_match('/^\//', $path) == 0) {
            $path = '/' . $path;
        }
        $fileId = '/' . $appId . '/' . $bucketName . $path;
        
        if (empty($secretId) || empty($secretKey) || empty($appId)) {
            return self::AUTH_SECRET_ID_KEY_ERROR;
        }
                    
        return self::appSignBase($appId, $secretId, $secretKey, 0, $fileId, $bucketName);
    }

    /**
     * 生成绑定资源的多次有效签名
     * @param  string $path       文件相对bucket的路径 /test/test.log 标识该bucket下test目录下的test.log文件
     * @param  string $bucketName bucket
     * @param  int $expired    过期时间，unix时间戳
     * @return string             签名串
     */
    public static function appSign_multiple($path, $bucketName, $expired) {

        $appId = Conf::APPID;
        $secretId = Conf::SECRET_ID;
        $secretKey = Conf::SECRET_KEY;

        if (preg_match('/^\//', $path) == 0) {
            $path = '/' . $path;
        }
        $fileId = $path;
        
        if (empty($secretId) || empty($secretKey) || empty($appId)) {
            return self::AUTH_SECRET_ID_KEY_ERROR;
        }
                    
        return self::appSignBase($appId, $secretId, $secretKey, $expired, $fileId, $bucketName);
    }
    
    /**
     * 签名函数（上传、下载会生成多次有效签名，删除资源会生成单次有效签名）
     * @param  string $appId
     * @param  string $secretId
     * @param  string $secretKey
     * @param  int $expired       过期时间,unix时间戳
     * @param  string $fileId     文件路径，以 /{$appId}/{$bucketName} 开头
     * @param  string $bucketName 文件所在bucket
     * @return string             签名
     */
    private static function appSignBase($appId, $secretId, $secretKey, $expired, $fileId, $bucketName) {
        if (empty($secretId) || empty($secretKey)) {
            return self::AUTH_SECRET_ID_KEY_ERROR;
        }

        $now = time();
        $rdm = rand();
        $plainText = "a=$appId&k=$secretId&e=$expired&t=$now&r=$rdm&f=$fileId&b=$bucketName";
        $bin = hash_hmac('SHA1', $plainText, $secretKey, true);
        $bin = $bin.$plainText;

        $sign = base64_encode($bin);

        return $sign;
    }

}

//end of script

