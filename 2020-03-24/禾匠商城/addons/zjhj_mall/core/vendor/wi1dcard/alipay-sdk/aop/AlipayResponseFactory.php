<?php

namespace Alipay;

use Alipay\Exception\AlipayInvalidResponseException;

class AlipayResponseFactory
{
    protected $format;

    /**
     * 创建响应解析器
     *
     * @param string $format 预期数据格式
     */
    public function __construct($format = 'JSON')
    {
        $this->format = $format;
    }

    public function getFormat()
    {
        return $this->format;
    }

    /**
     * 将 HTTP API 响应体字符串解析为结构化的对象
     *
     * @param string $raw 原始响应字符串
     *
     * @return AlipayResponse
     */
    public function parse($raw)
    {
        $data = json_decode($raw, true);
        if (!is_array($data)) {
            $error = function_exists('json_last_error_msg') ? json_last_error_msg() : json_last_error();

            throw new AlipayInvalidResponseException($raw, $error);
        }

        return new AlipayResponse($raw, $data);
    }
}
