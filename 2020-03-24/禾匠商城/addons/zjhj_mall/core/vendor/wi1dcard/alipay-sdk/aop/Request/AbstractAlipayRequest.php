<?php

namespace Alipay\Request;

use Alipay\AlipayAccessorTrait;

abstract class AbstractAlipayRequest
{
    use AlipayAccessorTrait;

    /**
     * API 请求参数（非系统参数）
     *
     * @var array
     */
    protected $apiParams = [];

    /**
     * 构建请求字符串时，是否将参数内的数组编码为 JSON
     *
     * @var bool
     */
    public $arrayAsJson = true;

    protected $notifyUrl;

    protected $returnUrl;

    // protected $terminalType;

    // protected $terminalInfo;

    // protected $prodCode;

    protected $authToken;

    protected $appAuthToken;

    public function __construct($config = [])
    {
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
    }

    public function __destruct()
    {
    }

    /**
     * 获取自身类名
     *
     * @param bool $shorten 是否不带命名空间
     *
     * @return string
     */
    public static function className($shorten = false)
    {
        $class = get_called_class();
        if ($shorten) {
            $class = (new \ReflectionClass($class))->getShortName();
        }

        return $class;
    }

    /**
     * 根据类名获取 API 方法名
     *
     * @return string
     */
    public static function getApiMethodName()
    {
        $api = static::className(true);
        $api = preg_replace('/Request$/', '', $api);
        $api = preg_replace('/([A-Z])/s', '.$1', $api);
        $api = trim($api, '.');
        $api = strtolower($api);

        return $api;
    }

    /**
     * 获取用于发起请求的“时间戳”
     *
     * @return string
     */
    public static function getTimestamp()
    {
        return date('Y-m-d H:i:s');
    }

    public function getApiParams()
    {
        return $this->apiParams;
    }

    public function getNotifyUrl()
    {
        return $this->notifyUrl;
    }

    public function setNotifyUrl($notifyUrl)
    {
        $this->notifyUrl = $notifyUrl;
    }

    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    public function setReturnUrl($returnUrl)
    {
        $this->returnUrl = $returnUrl;
    }

    // public function getTerminalType()
    // {
    //     return $this->terminalType;
    // }

    // public function setTerminalType($terminalType)
    // {
    //     $this->terminalType = $terminalType;
    // }

    // public function getTerminalInfo()
    // {
    //     return $this->terminalInfo;
    // }

    // public function setTerminalInfo($terminalInfo)
    // {
    //     $this->terminalInfo = $terminalInfo;
    // }

    // public function getProdCode()
    // {
    //     return $this->prodCode;
    // }

    // public function setProdCode($prodCode)
    // {
    //     $this->prodCode = $prodCode;
    // }

    public function getAuthToken()
    {
        return $this->authToken;
    }

    public function setAuthToken($authToken)
    {
        $this->authToken = $authToken;
    }

    public function getAppAuthToken()
    {
        return $this->appAuthToken;
    }

    public function setAppAuthToken($appAuthToken)
    {
        $this->appAuthToken = $appAuthToken;
    }
}
