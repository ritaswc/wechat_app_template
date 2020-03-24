<?php

namespace Alipay;

use Alipay\Exception\AlipayInvalidPropertyException;
use Alipay\Exception\AlipayInvalidRequestException;
use Alipay\Request\AbstractAlipayRequest;

class AlipayRequestFactory
{
    public $namespace = '';

    /**
     * 创建请求类工厂
     *
     * @param string $namespace
     */
    public function __construct($namespace = 'Alipay\Request\\')
    {
        $this->namespace = $namespace;
    }

    /**
     * 通过 `API 名称` 创建请求类实例
     *
     * @param string $apiName
     * @param array  $config
     *
     * @return AbstractAlipayRequest
     */
    public function createByApi($apiName, $config = [])
    {
        $className = AlipayHelper::studlyCase($apiName, '.') . 'Request';

        return $this->createByClass($className, $config);
    }

    /**
     * 通过 `请求类名` 创建请求类实例
     *
     * @param string $className
     * @param array  $config
     *
     * @return AbstractAlipayRequest
     */
    public function createByClass($className, $config = [])
    {
        $className = $this->namespace . $className;

        $this->validate($className);

        $instance = new $className();

        foreach ($config as $key => $value) {
            $property = AlipayHelper::studlyCase($key, '_');

            try {
                $instance->$property = $value;
            } catch (AlipayInvalidPropertyException $ex) {
                throw new AlipayInvalidRequestException($ex->getMessage() . ': ' . $key);
            }
        }

        return $instance;
    }

    /**
     * 验证某类可否被创建
     *
     * @param string $className
     *
     * @return void
     */
    protected function validate($className)
    {
        if (!class_exists($className)) {
            throw new AlipayInvalidRequestException("Class {$className} doesn't exist");
        }
        $abstractClass = AbstractAlipayRequest::className();
        if (!is_subclass_of($className, $abstractClass)) {
            throw new AlipayInvalidRequestException("Class {$className} doesn't extend {$abstractClass}");
        }
    }

    /**
     * 创建请求类实例
     *
     * @param string $classOrApi
     * @param array  $config
     *
     * @return AbstractAlipayRequest
     */
    public static function create($classOrApi, $config = [])
    {
        $factory = isset($this) ? $this : new self();

        if (strpos($classOrApi, '.')) {
            return $factory->createByApi($classOrApi, $config);
        } else {
            return $factory->createByClass($classOrApi, $config);
        }
    }
}
