<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/1/3
 * Time: 14:38
 */

namespace app\modules\mch\models;

use app\models\WechatApp;
use luweiss\wechat\DataTransform;
use luweiss\wechat\Wechat;

class WechatSettingForm extends MchModel
{
    /** @var WechatApp $model */
    public $model;
    public $app_id;
    public $app_secret;
    public $mch_id;
    public $key;
    public $cert_pem;
    public $key_pem;

    public function rules()
    {
        return [
            [['app_id', 'app_secret', 'mch_id', 'key', 'cert_pem', 'key_pem'], 'trim'],
            [['app_id', 'app_secret', 'mch_id', 'key', 'model'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'app_id' => '小程序AppId',
            'app_secret' => '小程序AppSecret',
            'mch_id' => '微信支付商户号',
            'key' => '微信支付Api密钥',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $this->model->attributes = $this->attributes;

        if (!is_dir(\Yii::$app->runtimePath . '/pem')) {
            mkdir(\Yii::$app->runtimePath . '/pem');
            file_put_contents(\Yii::$app->runtimePath . '/pem/index.html', '');
        }
        $cert_pem_file = null;
        if ($this->cert_pem) {
            $cert_pem_file = \Yii::$app->runtimePath . '/pem/' . md5($this->cert_pem);
            if (!file_exists($cert_pem_file)) {
                file_put_contents($cert_pem_file, $this->cert_pem);
            }
            if (!file_exists($cert_pem_file)) {
                return [
                    'code'=>1,
                    'msg'=>'证书读取不到'
                ];
            }
        }
        $key_pem_file = null;
        if ($this->key_pem) {
            $key_pem_file = \Yii::$app->runtimePath . '/pem/' . md5($this->key_pem);
            if (!file_exists($key_pem_file)) {
                file_put_contents($key_pem_file, $this->key_pem);
            }
            if (!file_exists($key_pem_file)) {
                return [
                    'code'=>1,
                    'msg'=>'证书读取不到'
                ];
            }
        }

        $wechat = new Wechat([
            'appId' => $this->app_id,
            'appSecret' => $this->app_secret,
            'mchId' => $this->mch_id,
            'apiKey' => $this->key,
            'certPem' => $cert_pem_file,
            'keyPem' => $key_pem_file,
        ]);

        $res = $wechat->getAccessToken(true);
        if ($wechat->errCode == 40013) {
            return [
                'code' => 1,
                'msg' => '小程序AppId错误'
            ];
        }
        if ($wechat->errCode == 40125) {
            return [
                'code' => 1,
                'msg' => '小程序AppSecret错误'
            ];
        }

        if ($wechat->curl->error_code == 58) {
            return [
                'code' => 1,
                'msg' => '微信支付证书错误'
            ];
        }
        if ($this->mch_id || $this->key || !($this->mch_id == 0 && $this->key == 0)) {
            $order_no = date('YmdHis') . mt_rand(1000, 9999);
            $res = $wechat->pay->orderQuery($order_no);
            if ($res['return_code'] == "FAIL") {
                return [
                    'code' => 1,
                    'msg' => '微信支付商户号或微信支付Api密钥错误--('.$res['return_msg'].')'
                ];
            }
        }
        if ($this->model->save()) {
            return [
                'code' => 0,
                'msg' => '保存成功',
            ];
        }
        return $this->getErrorResponse($this->model);
    }
}
