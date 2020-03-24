<?php

namespace Alipay\Exception;

/**
 * 验证签名时，若签名不匹配则抛出。
 */
class AlipayInvalidSignException extends AlipayException
{
    protected $sign;

    protected $data;

    public function __construct($sign, $data)
    {
        $this->sign = $sign;
        $this->data = $data;
        parent::__construct('Signature did not match');
    }

    public function getSign()
    {
        return $this->sign;
    }

    public function getData()
    {
        return $this->data;
    }
}
