<?php
namespace Flc\Alidayu;

use Exception;
use Flc\Alidayu\Requests\IRequest;

/**
 * 阿里大于客户端
 *
 * @author Flc <2016-09-18 19:43:18>
 * @link   http://flc.ren
 */
class Client
{   
    /**
     * API请求地址
     * @var string
     */
    protected $api_uri = 'http://gw.api.taobao.com/router/rest';

    /**
     * 沙箱请求地址
     * @var string
     */
    protected $api_sandbox_uri = 'http://gw.api.tbsandbox.com/router/rest'; 

    /**
     * 应用
     * @var \Flc\Alidayu\App
     */
    protected $app;

    /**
     * 签名规则
     * @var string
     */
    protected $sign_method = 'md5';

    /**
     * 响应格式。可选值：xml，json。
     * @var string
     */
    protected $format = 'json';

    /**
     * 静态配置
     * @var array
     */
    protected static $config = [];

    /**
     * 初始化
     * @param array $config 阿里大于配置App类
     */
    public function __construct(App $app)
    {
        $this->app = $app;

        // 判断配置
        if (empty($this->app->app_key) || empty($this->app->app_secret)) {
            throw new Exception("阿里大于配置信息：app_key或app_secret错误");
        }
    }

    /**
     * 发起请求数据
     * @param  \Flc\Alidayu\Requests\IRequest $request 请求类
     * @return false|object
     */
    public function execute(IRequest $request)
    {
        $method        = $request->getMethod();
        $publicParams  = $this->getPublicParams();
        $serviceParams = $request->getParams();

        $params = array_merge(
            $publicParams,
            [
                'method' => $method
            ],
            $serviceParams
        );

        // 签名
        $params['sign'] = $this->generateSign($params);

        // 请求数据
        $resp = $this->curl(
            $this->app->sandbox ? $this->api_sandbox_uri : $this->api_uri,
            $params
        );

        // 解析返回
        return $this->parseRep($resp);
    }

    /**
     * 设置签名方式
     * @param string $value 签名方式，支持md5, hmac
     */
    public function setSignMethod($value = 'md5')
    {
        $this->sign_method = $value;

        return $this;
    }

    /**
     * 设置回传格式
     * @param string $value 响应格式，支持json/xml
     */
    public function setFormat($value = 'json')
    {
        $this->format = $value;

        return $this;
    }

    /**
     * 解析返回数据
     * @return array|false
     */
    protected function parseRep($response)
    {
        if ($this->format == 'json') {
            $resp = json_decode($response);

            if (false !== $resp) {
                $resp = @current($resp);
            }
        }

        elseif ($this->format == 'xml') {
            $resp = @simplexml_load_string($response);
        }

        else {
            throw new Exception("format错误...");
        }

        return $resp;
    }

    /**
     * 返回公共参数
     * @return array 
     */
    protected function getPublicParams()
    {
        return [
            'app_key'     => $this->app->app_key,
            'timestamp'   => date('Y-m-d H:i:s'),
            'format'      => $this->format,
            'v'           => '2.0',
            'sign_method' => $this->sign_method
        ];
    }

    /**
     * 生成签名
     * @param  array  $params 待签参数
     * @return string         
     */
    protected function generateSign($params = [])
    {
        if ($this->sign_method == 'md5') {
            return $this->generateMd5Sign($params);
        } elseif ($this->sign_method == 'hmac') {
            return $this->generateHmacSign($params);
        } else {
            throw new Exception("sign_method错误...");
        }
    }

    /**
     * 按Md5方式生成签名
     * @param  array  $params 待签的参数
     * @return string         
     */
    protected function generateMd5Sign($params = [])
    {
        static::sortParams($params);  // 排序

        $arr = [];
        foreach ($params as $k => $v) {
            $arr[] = $k . $v;
        }
        
        $str = $this->app->app_secret . implode('', $arr) . $this->app->app_secret;

        return strtoupper(md5($str));
    }

    /**
     * 按hmac方式生成签名
     * @param  array  $params 待签的参数
     * @return string         
     */
    protected function generateHmacSign($params = [])
    {
        static::sortParams($params);  // 排序

        $arr = [];
        foreach ($params as $k => $v) {
            $arr[] = $k . $v;
        }
        
        $str = implode('', $arr);

        return strtoupper(hash_hmac('md5', $str, $this->app->app_secret));
    }

    /**
     * 待签名参数排序
     * @param  array  &$params 参数
     * @return array         
     */
    protected static function sortParams(&$params = [])
    {
        ksort($params);
    }

    /**
     * 请求API
     * @param  string   $method   接口名称
     * @param  callable $callable 执行函数
     * @return [type]             [description]
     */
    public static function request($method, callable $callable)
    {
        // A. 校验
        if (empty($method) ||
            ! $classname = self::getMethodClassName($method)
        ) {
            throw new Exception("method错误");
        }

        // B. 获取带命名空间的类
        $classNameSpace = __NAMESPACE__ . '\\Requests\\' . $classname;

        if (! class_exists($classNameSpace)) 
            throw new Exception("method不存在");

        // C. 创建对象
        $request = new $classNameSpace;

        // D. 执行匿名函数
        if (is_callable($callable)) {
            call_user_func($callable, $request);
        }

        // E. 创建CLIENT对象
        $client = new self(new App(self::$config));

        return call_user_func_array([$client, 'execute'], [$request]);
    }

    /**
     * 静态配置（全局）
     * @param  array $config 配置项
     * @return [type]         [description]
     */
    public static function configure($config)
    {
        self::$config = $config;
    }

    /**
     * 通过接口名称获取对应的类名称
     * @param  string $method 接口名称
     * @return string         
     */
    protected static function getMethodClassName($method)
    {
        $methods = explode('.', $method);
        
        if (!is_array($methods))
            return false;

        $tmp = array();

        foreach ($methods as $value) {
            $tmp[] = ucwords($value);
        }

        $className = implode('', $tmp);

        return $className;
    }

    /**
     * curl请求
     * @param  string $url        string
     * @param  array|null $postFields 请求参数
     * @return [type]             [description]
     */
    protected function curl($url, $postFields = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //https 请求
        if(strlen($url) > 5 && strtolower(substr($url,0,5)) == "https" ) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        if (is_array($postFields) && 0 < count($postFields)) {
            $postBodyString = "";
            foreach ($postFields as $k => $v) {
                $postBodyString .= "$k=" . urlencode($v) . "&"; 
            }
            unset($k, $v);
            curl_setopt($ch, CURLOPT_POST, true);
            $header = array("content-type: application/x-www-form-urlencoded; charset=UTF-8");
            curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
            curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString,0,-1));
        }
        $reponse = curl_exec($ch);
        return $reponse;
    }
}
