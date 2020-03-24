<?php

namespace Alipay\Exception;

/**
 * 当试图获取响应数据字段，但响应码为失败或错误时抛出。
 * 注意：此异常并非在 CURL 通讯错误、响应格式异常、响应无法解析时抛出！
 *
 * @see AlipayInvalidResponseException
 */
class AlipayErrorResponseException extends AlipayException
{
    /**
     * @param array $error
     */
    public function __construct($error)
    {
        $message = isset($error['msg']) ? $error['msg'] : '';
        $message .= isset($error['sub_msg']) ? ': ' . $error['sub_msg'] : '';
        $code = isset($error['code']) ? $error['code'] : 0;
        parent::__construct($message, $code);
    }
}
