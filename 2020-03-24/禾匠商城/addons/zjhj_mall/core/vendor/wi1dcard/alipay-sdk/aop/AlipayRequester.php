<?php

namespace Alipay;

class AlipayRequester
{
    protected $gateway;

    protected $charset;

    protected $callback;

    public function __construct(
        callable $callback,
        $gateway = 'https://openapi.alipay.com/gateway.do',
        $charset = 'UTF-8'
    ) {
        $this->callback = $callback;
        $this->gateway = $gateway;
        $this->charset = $charset;
    }

    public function getGateway()
    {
        return $this->gateway;
    }

    public function getCharset()
    {
        return $this->charset;
    }

    public function getUrl()
    {
        return $this->getGateway() . '?charset=' . urlencode($this->getCharset());
    }

    /**
     * 提交请求
     *
     * @param array $params
     *
     * @return mixed
     */
    public function execute($params)
    {
        return call_user_func($this->callback, $this->getUrl(), $params);
    }
}
