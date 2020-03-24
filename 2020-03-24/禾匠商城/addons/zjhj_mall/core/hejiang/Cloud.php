<?php

namespace app\hejiang;

use Curl\Curl;
use yii\helpers\VarDumper;

class Cloud
{
    public static $api_key = 'odhjqowdja8u298yhqd9qwydasyioh230912doj238';
    public static $cloud_server_prefix = 'http://';
    public static $cloud_server_host = 'cloud.zjhejiang.com';
    //public static $cloud_server_host = 'localhost/php/cloud_zjhejiang/web';

    private static $curl;

    public static $product = 'mall';

    public static $auth_info;

    /**
     * @return Curl
     */
    public static function getCurl()
    {
        if (self::$curl) {
            return self::$curl;
        }
        self::$curl = new Curl();
        self::$curl->setOpt(CURLOPT_SSL_VERIFYHOST, false);
        self::$curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        return self::$curl;
    }

    /**
     * @return array ['version'=>$version,'host'=>$domain]
     */
    public static function getSiteInfo()
    {
        $version_file = \Yii::$app->basePath . '/version.json';
        if (file_exists($version_file) && $version = json_decode(file_get_contents($version_file), true)) {
            $version = isset($version['version']) ? $version['version'] : null;
        } else {
            $version = null;
        }
        $host = \Yii::$app->request->hostName;
        $site_info = [
            'version' => $version,
            'host' => $host,
        ];
        return $site_info;
    }


    public static function apiGet($url, $data = [])
    {
        $site_info = self::getSiteInfo();
        $get_data = base64_encode(json_encode([
            'host' => $site_info['host'],
            'current_version' => $site_info['version'],
            'from_url' =>str_replace( \Yii::$app->request->hostName,'www.xkedou.cn', \Yii::$app->request->absoluteUrl),
        ]));
        $data = array_merge($data, [
            'data' => $get_data,
        ]);
        $curl = self::getCurl();
        $curl->get($url, $data);
        return $curl;
    }

    public static function apiPost($url, $data = [])
    {
        $site_info = self::getSiteInfo();
        $get_data = base64_encode(json_encode([
            'host' => $site_info['host'],
            'current_version' => $site_info['version'],
            'from_url' => \Yii::$app->request->absoluteUrl,
        ]));
        $url = $url . '?data=' . $get_data;
        $curl = self::getCurl();
        $curl->post($url, $data);
        return $curl;
    }

    public static function api2Get($url, $data = [])
    {
        $site_info = self::getSiteInfo();
        $data = array_merge($data, [
            '_domain' => $site_info['host'],
            '_version' => $site_info['version'],
            '_product' => self::$product,
        ]);
        $curl = self::getCurl();
        $curl->get($url, $data);
        return $curl;
    }

    public static function api2Post($url, $data = [])
    {
        $site_info = self::getSiteInfo();
        $query_str = http_build_query([
            '_domain' => $site_info['host'],
            '_version' => $site_info['version'],
            '_product' => self::$product,
        ]);
        $url = $url . '?' . $query_str;
        $curl = self::getCurl();
        $curl->get($url, $data);
        return $curl;
    }


    /**
     * @return array|null ['code'=>0|1,'msg'=>msg,'data'=>['domain'=>domain,'ip'=>ip]]
     */
    public static function getAuthInfo()
    {
		 self::$auth_info = json_decode('{"code":0,"data":{"account_max":"-1","expire":7200},"msg":""}', true);
        if (self::$auth_info)
            return self::$auth_info;
        $cache_key = 'HEJIANG_AUTH_INFO';
        if (\Yii::$app->cache->get($cache_key)) {
            self::$auth_info = \Yii::$app->cache->get($cache_key);
            return self::$auth_info;
        }
        $api = Cloud::$cloud_server_prefix . Cloud::$cloud_server_host . '/api2/default/auth-info';
        $curl = Cloud::api2Get($api);
        if (!$curl->response) {
            self::$auth_info = [
                'code' => 1,
                'msg' => '服务器授权失败，云服务器连接出错',
            ];
        }
       self::$auth_info = json_decode($curl->response, true);
        if (self::$auth_info['code'] == 0) {
            \Yii::$app->cache->set($cache_key, self::$auth_info, isset(self::$auth_info['data']['expire']) ? self::$auth_info['data']['expire'] : 3600);
        }
        return self::$auth_info;
    }

    public static function checkAuth()
    {
		
       return true;
    }

}