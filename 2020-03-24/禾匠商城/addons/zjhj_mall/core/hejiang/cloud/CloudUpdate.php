<?php
/**
 * @copyright ©2018 Lu Wei
 * @author Lu Wei
 * @link http://www.luweiss.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/12/3 9:22
 */


namespace app\hejiang\cloud;


class CloudUpdate
{
    const REQUEST_METHOD_GET = 'REQUEST_METHOD_GET';
    const REQUEST_METHOD_POST = 'REQUEST_METHOD_POST';

    public static function getData()
    {
        return static::sendRequest(static::REQUEST_METHOD_GET, CloudApi::UPDATE_DATA);
    }

    /**
     * @param $method
     * @param $url
     * @param array $params
     * @param array $data
     * @return mixed
     * @throws CloudException
     */
    private static function sendRequest($method, $url, $params = [], $data = [])
    {
        if ($method === static::REQUEST_METHOD_GET) {
            $response = HttpClient::get($url, $params);
        } elseif ($method === static::REQUEST_METHOD_POST) {
            $response = HttpClient::post($url, $data, $params);
        } else {
            throw new \Exception('系统错误：无效的method');
        }
        $res = json_decode($response, true);
        if (!$res) {
            throw new \Exception('网络错误：' . $response);
        }
        if (isset($res['request'])) {
            unset($res['request']);
        }
        return $res;
    }
}