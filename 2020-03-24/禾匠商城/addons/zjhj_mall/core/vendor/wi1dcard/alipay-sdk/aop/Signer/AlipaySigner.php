<?php

namespace Alipay\Signer;

use Alipay\AlipayHelper;
use Alipay\Exception\AlipayBase64Exception;
use Alipay\Exception\AlipayInvalidSignException;
use Alipay\Exception\AlipayOpenSslException;

abstract class AlipaySigner
{
    /**
     * 支付宝服务器发起回调通知时，使用的「签名」参数名
     */
    const SIGN_PARAM = 'sign';

    /**
     * 支付宝服务器发起回调通知时，使用的「签名类型」参数名
     */
    const SIGN_TYPE_PARAM = 'sign_type';

    /**
     * 签名（计算 Sign 值）
     *
     * @param string   $data
     * @param resource $privateKey
     *
     * @throws AlipayOpenSslException
     * @throws AlipayBase64Exception
     *
     * @return string
     *
     * @see https://docs.open.alipay.com/291/106118
     */
    public function generate($data, $privateKey)
    {
        $result = openssl_sign($data, $sign, $privateKey, $this->getSignAlgo());
        if ($result === false) {
            throw new AlipayOpenSslException();
        }

        return base64_encode($sign);
    }

    /**
     * 将参数数组签名（计算 Sign 值）
     *
     * @param array    $params
     * @param resource $privateKey
     *
     * @return string
     *
     * @see self::generate()
     */
    public function generateByParams($params, $privateKey)
    {
        $data = $this->convertSignData($params);

        return $this->generate($data, $privateKey);
    }

    /**
     * 验签（验证 Sign 值）
     *
     * @param string   $sign
     * @param string   $data
     * @param resource $publicKey
     *
     * @throws AlipayBase64Exception
     * @throws AlipayInvalidSignException
     * @throws AlipayOpenSslException
     *
     * @return void
     *
     * @see https://docs.open.alipay.com/200/106120
     */
    public function verify($sign, $data, $publicKey)
    {
        $decodedSign = base64_decode($sign, true);
        if ($decodedSign === false) {
            throw new AlipayBase64Exception($sign);
        }
        $result = openssl_verify($data, $decodedSign, $publicKey, $this->getSignAlgo());
        switch ($result) {
            case 1:
                break;
            case 0:
                throw new AlipayInvalidSignException($sign, $data);
            case -1:
                // no break
            default:
                throw new AlipayOpenSslException();
        }
    }

    /**
     * 异步通知验签（验证 Sign 值）
     *
     * @param array    $params
     * @param resource $publicKey
     *
     * @return array
     *
     * @see self::verify()
     * @see https://docs.open.alipay.com/200/106120#s1
     */
    public function verifyByParams($params, $publicKey)
    {
        if (!isset($params[static::SIGN_PARAM]) || !isset($params[static::SIGN_TYPE_PARAM])) {
            throw new \InvalidArgumentException('Missing signature arguments');
        }

        $sign = $params[static::SIGN_PARAM];
        $signType = $params[static::SIGN_TYPE_PARAM];
        unset($params[static::SIGN_PARAM], $params[static::SIGN_TYPE_PARAM]);

        if ($signType !== $this->getSignType()) {
            throw new \InvalidArgumentException("Sign type didn't match, expect {$this->getSignType()}, {$signType} given");
        }

        $data = $this->convertSignData($params);
        $this->verify($sign, $data, $publicKey);

        return $params;
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

    abstract public function getSignType();

    abstract public function getSignAlgo();
}
