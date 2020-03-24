<?php

namespace Alipay\Exception;

/**
 * 当操作请求类内的无效属性时抛出。
 * 包括但不限于：试图改写只读属性、读取不存在属性等。
 */
class AlipayInvalidPropertyException extends AlipayException
{
    protected $property;

    public function __construct($message, $property = '')
    {
        $this->property = $property;
        parent::__construct($message);
    }

    public function getProperty()
    {
        return $this->property;
    }
}
