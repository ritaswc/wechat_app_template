<?php

namespace Alipay\Exception;

/**
 * 当响应无法被解析器解析时抛出。
 * 包括但不限于：响应格式错误、响应被篡改等。
 */
class AlipayInvalidResponseException extends AlipayException
{
    protected $response;

    public function __construct($response, $message = '')
    {
        $this->response = $response;

        $message = $message == '' ? '' : $message . ': ';
        $message .= $response;

        parent::__construct($message);
    }

    public function getResponse()
    {
        return $this->response;
    }
}
