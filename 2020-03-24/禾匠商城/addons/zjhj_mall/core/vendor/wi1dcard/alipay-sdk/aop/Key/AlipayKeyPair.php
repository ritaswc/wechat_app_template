<?php

namespace Alipay\Key;

use Alipay\Exception\AlipayOpenSslException;

class AlipayKeyPair
{
    /**
     * 支付宝公钥
     * 支持文件路径或公钥字符串，用于验证签名
     *
     * @var AlipayPublicKey
     */
    protected $publicKey;

    /**
     * 商户私钥（又称：小程序私钥，App私钥等）
     * 支持文件路径或私钥字符串，用于生成签名
     *
     * @var AlipayPrivateKey
     */
    protected $privateKey;

    /**
     * 创建密钥对
     *
     * @param string $privateKey
     * @param string $publicKey
     *
     * @return static
     */
    public static function create($privateKey, $publicKey)
    {
        $instance = new static();
        $instance->setPrivateKey($privateKey);
        $instance->setPublicKey($publicKey);

        return $instance;
    }

    /**
     * 生成密钥对
     *
     * @param array $configargs
     *
     * @return static
     */
    public static function generate($configargs = [])
    {
        $configargs = array_merge([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ], $configargs);

        $resource = openssl_pkey_new($configargs);

        if ($resource === false) {
            throw new AlipayOpenSslException();
        }

        $instance = new static();
        $instance->privateKey = AlipayPrivateKey::fromResource($resource);
        $instance->publicKey = AlipayPublicKey::create(
            AlipayPublicKey::toString($resource)
        );

        return $instance;
    }

    /**
     * 获取私钥对象
     *
     * @return AlipayPrivateKey
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * 获取公钥对象
     *
     * @return AlipayPublicKey
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * 设置公钥
     *
     * @param string $key
     *
     * @return static
     */
    public function setPrivateKey($key)
    {
        $this->privateKey = AlipayPrivateKey::create($key);

        return $this;
    }

    /**
     * 设置私钥
     *
     * @param string $key
     *
     * @return static
     */
    public function setPublicKey($key)
    {
        $this->publicKey = AlipayPublicKey::create($key);

        return $this;
    }
}
