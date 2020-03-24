<?php

namespace luweiss\wechat;

use Curl\Curl;
use Doctrine\Common\Cache\FilesystemCache;

/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/5/26
 * Time: 9:55
 */
class Wechat
{
    public $errMsg = 0;
    public $errCode;

    public $appId;
    public $appSecret;
    public $mchId;
    public $apiKey;
    public $certPem;
    public $keyPem;


    /**
     * 微信支付组件
     *
     * @var Pay
     */
    public $pay;


    /**
     * Jsapi组件
     *
     * @var Jsapi
     */
    public $jsapi;


    /**
     * 模板消息组件
     *
     * @var TplMsg
     */
    public $tplMsg;


    /**
     * 缓存路径
     *
     * @var string
     */
    public $cachePath;


    /**
     * 缓存组件
     *
     * @var FilesystemCache
     */
    public $cache;


    /**
     * CURL
     *
     * @var Curl
     */
    public $curl;


    /**
     * 初始化
     *
     * @param array $args 初始化参数
     *
     * [
     *
     * 'appId' => '公众号appId',
     *
     * 'appSecret' => '公众号appSecret',
     *
     * 'mchId' => '微信支付商户id',
     *
     * 'apiKey' => '微信支付api密钥',
     *
     * 'certPem' => '微信支付cert证书路径（系统完整路径）',
     *
     * 'keyPem' => '微信支付key证书路径（系统完整路径）',
     *
     * 'cachePath' => '缓存路径（系统完整路径）',
     *
     * ]
     * @return Wechat|null
     */
    public function __construct($args = [])
    {
        $this->appId = isset($args['appId']) ? $args['appId'] : null;
        $this->appSecret = isset($args['appSecret']) ? $args['appSecret'] : null;
        $this->mchId = isset($args['mchId']) ? $args['mchId'] : null;
        $this->apiKey = isset($args['apiKey']) ? $args['apiKey'] : null;
        $this->certPem = isset($args['certPem']) ? $args['certPem'] : null;
        $this->keyPem = isset($args['keyPem']) ? $args['keyPem'] : null;
        $this->cachePath = isset($args['cachePath']) ? $args['cachePath'] : null;
        return $this->init();
    }


    private function init()
    {
        if (!$this->cachePath)
            $this->cachePath = dirname(__DIR__) . '/runtime/cache';
        $this->cache = new FilesystemCache($this->cachePath);
        $this->curl = new Curl();
        $this->curl->setOpt(CURLOPT_SSL_VERIFYHOST, false);
        $this->curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);

        if ($this->certPem) {
            $this->curl->setOpt(CURLOPT_SSLCERTTYPE, 'PEM');
            $this->curl->setOpt(CURLOPT_SSLCERT, $this->certPem);
        }
        if ($this->keyPem) {
            $this->curl->setOpt(CURLOPT_SSLCERTTYPE, 'PEM');
            $this->curl->setOpt(CURLOPT_SSLKEY, $this->keyPem);
        }

        $this->pay = new Pay($this);
        $this->jsapi = new Jsapi($this);
        $this->tplMsg = new TplMsg($this);
        return $this;
    }

    /**
     * 获取微信接口的accessToken
     *
     * @param boolean $refresh 是否刷新accessToken
     * @param integer $expires accessToken缓存时间（秒）
     * @return string|null
     */
    public function getAccessToken($refresh = false, $expires = 3600)
    {
        $cacheKey = md5("{$this->appId}@access_token");
        $accessToken = $this->cache->fetch($cacheKey);
        $accessTokenOk = $this->checkAccessToken($accessToken);
        if (!$accessToken || $refresh || !$accessTokenOk) {
            $api = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appId}&secret={$this->appSecret}";
            $this->curl->get($api);
            $res = json_decode($this->curl->response, true);
            if (empty($res['access_token'])) {
                $this->errCode = isset($res['errcode']) ? $res['errcode'] : null;
                $this->errMsg = isset($res['errmsg']) ? $res['errmsg'] : null;
                return false;
            }
            $accessToken = $res['access_token'];
            $this->cache->save($cacheKey, $accessToken, $expires);
            return $accessToken;
        } else {
            return $accessToken;
        }
    }

    private function checkAccessToken($accessToken)
    {
        if (!$accessToken)
            return false;
        $api = "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token={$accessToken}";
        $this->curl->get($api);
        $res = json_decode($this->curl->response, true);
        if (!empty($res['errcode']) && $res['errcode'] != 1)
            return false;
        return true;
    }


}