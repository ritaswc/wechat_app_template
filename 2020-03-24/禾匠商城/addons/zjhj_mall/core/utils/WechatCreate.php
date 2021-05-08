<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/10
 * Time: 18:22
 */

namespace app\utils;


use app\models\Store;
use app\models\WechatApp;
use luweiss\wechat\Wechat;

class WechatCreate
{
    public static function create(Store $store)
    {
        $wechatApp = WechatApp::findOne(['id' => $store->wechat_app_id]);

        if (!is_dir(\Yii::$app->runtimePath . '/pem')) {
            mkdir(\Yii::$app->runtimePath . '/pem');
            file_put_contents(\Yii::$app->runtimePath . '/pem/index.html', '');
        }
        $cert_pem_file = null;
        if ($wechatApp->cert_pem) {
            $cert_pem_file = \Yii::$app->runtimePath . '/pem/' . md5($wechatApp->cert_pem);
            if (!file_exists($cert_pem_file)) {
                file_put_contents($cert_pem_file, $wechatApp->cert_pem);
            }
        }
        $key_pem_file = null;
        if ($wechatApp->key_pem) {
            $key_pem_file = \Yii::$app->runtimePath . '/pem/' . md5($wechatApp->key_pem);
            if (!file_exists($key_pem_file)) {
                file_put_contents($key_pem_file, $wechatApp->key_pem);
            }
        }
        return new Wechat([
            'appId' => $wechatApp->app_id,
            'appSecret' => $wechatApp->app_secret,
            'mchId' => $wechatApp->mch_id,
            'apiKey' => $wechatApp->key,
            'certPem' => $cert_pem_file,
            'keyPem' => $key_pem_file,
        ]);
    }
}