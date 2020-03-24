<?php
/**
 * Created by PhpStorm.
 * User: jiehua
 * Date: 15/5/2
 * Time: 下午6:21
 */

namespace Alipay;

use Alipay\Exception\AlipayBase64Exception;
use Alipay\Exception\AlipayInvalidKeyException;
use Alipay\Exception\AlipayInvalidSignException;
use Alipay\Exception\AlipayOpenSslException;

class AlipaySigner
{
    /**
     * 签名类型
     *
     * @var string
     */
    protected $type = 'RSA2';

    /**
     * 商户私钥（又称：小程序私钥，App私钥等）
     * 支持文件路径或私钥字符串，用于生成签名
     *
     * @var string
     */
    protected $appPrivateKey;

    protected $appPrivateKeyResource;

    /**
     * 支付宝公钥
     * 支持文件路径或公钥字符串，用于验证签名
     *
     * @var string
     */
    protected $alipayPublicKey;

    protected $alipayPublicKeyResource;

    /**
     * 创建 AlipaySigner 实例
     *
     * @param string $signType
     * @param string $appPrivateKey
     * @param string $alipayPublicKey
     *
     * @throws \InvalidArgumentException
     *
     * @return static
     */
    public static function create($appPrivateKey, $alipayPublicKey, $signType = 'RSA2')
    {
        $instance = new static();
        $typeAlgoMap = $instance->typeAlgoMap();
        if (!isset($typeAlgoMap[$signType])) {
            throw new \InvalidArgumentException('Unknown sign type: ' . $signType);
        }
        $instance->type = $signType;
        $instance->appPrivateKey = $appPrivateKey;
        $instance->alipayPublicKey = $alipayPublicKey;
        $instance->loadKeys();

        return $instance;
    }

    /**
     * 构造函数
     */
    protected function __construct()
    {
    }

    /**
     * 析构函数
     */
    public function __destruct()
    {
        $this->freeKeys();
    }

    /**
     * 深拷贝需要重新加载密钥，以防密钥被释放
     *
     * @return void
     */
    public function __clone()
    {
        $this->loadKeys();
    }

    /**
     * 加载公钥和私钥
     *
     * @return void
     *
     * @see self::getKey()
     */
    protected function loadKeys()
    {
        $this->appPrivateKeyResource = $this->getKey($this->appPrivateKey, true);
        $this->alipayPublicKeyResource = $this->getKey($this->alipayPublicKey, false);
    }

    /**
     * 释放公钥和私钥资源
     *
     * @return void
     */
    protected function freeKeys()
    {
        $freeKey = function ($k) {
            if (is_resource($k)) {
                openssl_free_key($k);
            }
        };
        $freeKey($this->appPrivateKeyResource);
        $freeKey($this->alipayPublicKeyResource);
    }

    /**
     * 签名（计算 Sign 值）
     *
     * @param string $data
     *
     * @throws AlipayOpenSslException
     * @throws AlipayBase64Exception
     *
     * @return string
     *
     * @see https://docs.open.alipay.com/291/106118
     */
    public function generate($data)
    {
        $result = openssl_sign($data, $sign, $this->appPrivateKeyResource, $this->getSignAlgo());
        if ($result === false) {
            throw new AlipayOpenSslException(openssl_error_string());
        }
        $encodedSign = base64_encode($sign);
        if ($encodedSign === false) {
            throw new AlipayBase64Exception($sign, true);
        }

        return $encodedSign;
    }

    /**
     * 将参数数组签名（计算 Sign 值）
     *
     * @param array $params
     *
     * @return string
     *
     * @see self::generate
     */
    public function generateByParams($params)
    {
        $data = $this->convertSignData($params);

        return $this->generate($data);
    }

    /**
     * 验签（验证 Sign 值）
     *
     * @param string $sign
     * @param string $data
     *
     * @throws AlipayBase64Exception
     * @throws AlipayInvalidSignException
     * @throws AlipayOpenSslException
     *
     * @return void
     *
     * @see https://docs.open.alipay.com/200/106120
     */
    public function verify($sign, $data)
    {
        $decodedSign = base64_decode($sign, true);
        if ($decodedSign === false) {
            throw new AlipayBase64Exception($sign, false);
        }
        $result = openssl_verify($data, $decodedSign, $this->alipayPublicKeyResource, $this->getSignAlgo());
        switch ($result) {
            case 1:
                break;
            case 0:
                throw new AlipayInvalidSignException($sign, $data);
            case -1:
                throw new AlipayOpenSslException(openssl_error_string());
        }
    }

    /**
     * 异步通知验签（验证 Sign 值）
     *
     * @param array $params
     *
     * @return void
     *
     * @see self::verify
     * @see https://docs.open.alipay.com/200/106120#s1
     */
    public function verifyByAsyncCallback($params)
    {
        $sign = $params['sign'];
        $signType = $params['sign_type'];
        unset($params['sign'], $params['sign_type']);

        $data = $this->convertSignData($params);

        $copy = clone $this;
        $copy->type = $signType;
        $copy->verify($sign, $data);
    }

    /**
     * 使用密钥字符串或路径加载密钥
     *
     * @param string $keyOrFilePath
     * @param bool   $isPrivate
     *
     * @throws AlipayInvalidKeyException
     *
     * @return resource
     */
    protected function getKey($keyOrFilePath, $isPrivate = true)
    {
        if (file_exists($keyOrFilePath) && is_file($keyOrFilePath)) {
            $key = file_get_contents($keyOrFilePath);
        } else {
            $key = $keyOrFilePath;
        }
        if ($isPrivate) {
            $keyResource = openssl_pkey_get_private($key);
        } else {
            $keyResource = openssl_pkey_get_public($key);
        }
        if ($keyResource === false) {
            throw new AlipayInvalidKeyException('Invalid key: ' . $keyOrFilePath);
        }

        return $keyResource;
    }

    /**
     * 将数组转换为待签名数据
     *
     * @param array $params
     *
     * @return string
     */
    protected function convertSignData($params)
    {
        ksort($params);
        $stringToBeSigned = '';
        foreach ($params as $k => $v) {
            $v = @(string) $v;
            if (AlipayHelper::isEmpty($v) || $v[0] === '@') {
                continue;
            }
            $stringToBeSigned .= "&{$k}={$v}";
        }
        $stringToBeSigned = substr($stringToBeSigned, 1);

        return $stringToBeSigned;
    }

    /**
     * `签名类型 => 签名算法` 映射表
     *
     * @return int[]
     */
    protected function typeAlgoMap()
    {
        return [
            'RSA'  => OPENSSL_ALGO_SHA1,
            'RSA2' => OPENSSL_ALGO_SHA256,
        ];
    }

    /**
     * 获取签名类型
     *
     * @return string
     */
    public function getSignType()
    {
        return $this->type;
    }

    /**
     * 获取签名算法
     *
     * @return int
     */
    public function getSignAlgo()
    {
        return $this->typeAlgoMap()[$this->type];
    }
}
