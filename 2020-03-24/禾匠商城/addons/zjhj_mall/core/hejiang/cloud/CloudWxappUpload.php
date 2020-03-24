<?php
/**
 * @copyright ©2018 Lu Wei
 * @author Lu Wei
 * @link http://www.luweiss.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/12/4 9:26
 */


namespace app\hejiang\cloud;


class CloudWxappUpload
{
    /**
     * @param $args ['store_id','appid','branch',''jump_appid_list]
     * @return array|mixed
     */
    public static function login($args)
    {
        return static::sendRequest(CloudApi::APP_UPLOAD_LOGIN, $args);
    }

    /**
     * @param $args ['store_id','appid','branch',''jump_appid_list]
     * @return array|mixed
     */
    public static function preview($args)
    {
        return static::sendRequest(CloudApi::APP_UPLOAD_PREVIEW, $args);
    }

    /**
     * @param $args ['store_id','appid','branch',''jump_appid_list]
     * @return array|mixed
     */
    public static function upload($args)
    {
        return static::sendRequest(CloudApi::APP_UPLOAD_UPLOAD, $args);
    }

    private static function sendRequest($url, $args)
    {
        $args['token'] = static::getToken();
        $args['api_root'] = \Yii::$app->request->baseUrl . '/index.php?store_id=' . $args['store_id'] . '&r=api/';
        $args['version'] = hj_core_version();
        HttpClient::$curlTimeout = 120;
        $response = HttpClient::get($url, $args);
        $res = json_decode($response, true);
        if (!$res) {
            return [
                'code' => 1,
                'msg' => '系统错误: ' . $response,
            ];
        }
        return $res;
    }

    private static function getToken()
    {
        $token = \Yii::$app->session->get('__wxapp_upload_token');
        if (!$token) {
            $token = \Yii::$app->security->generateRandomString();
            \Yii::$app->session->set('__wxapp_upload_token', $token);
        }
        return $token;
    }
}
