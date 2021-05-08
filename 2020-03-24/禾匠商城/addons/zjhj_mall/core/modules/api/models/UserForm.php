<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/4/25
 * Time: 9:36
 */

namespace app\modules\api\models;

use app\models\User;
use app\modules\api\models\wxbdc\WXBizDataCrypt;
use Curl\Curl;

class UserForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $appId;
    public $code;
    public $encryptedData;
    public $iv;
    public $wechat_app;
    public $phone;
    public $phone_code;
    public $bind_type;
    public $binding;

    public function rules()
    {
        return [
            [['user_id',], 'required'],
            [['binding', 'phone_code', 'bind_type'], 'integer'],
            [['appId', 'code', 'encryptedData', 'iv', 'wechat_app','phone'], 'trim'],
            [['appId', 'code', 'encryptedData', 'iv', 'wechat_app','phone'], 'string'],
            [['phone'],'match','pattern' =>\app\models\Model::MOBILE_PATTERN , 'message'=>'手机号错误']
        ];
    }

    public function userEmpower()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        if ($this->bind_type != 2) {
            return [
                'code'=>1,
                'msg'=>'参数错误'
            ];
        }
        $user = user::find()->where(['store_id' => $this->store_id, 'id' => $this->user_id])->one();
        $mobile = $this->phone;
        if (!$mobile) {
            return [
                'code'=>1,
                'msg'=>'请输入手机号'
            ];
        }
        $message = \Yii::$app->cache->get('code_cache'.$mobile);
        if (!$this->phone_code) {
            return [
                'code'=>1,
                'msg'=>'请输入验证码'
            ];
        }
        if (!$message) {
            return [
                'code'=>1,
                'msg'=>'请先发送短信'
            ];
        }
        if (!$message->validate(intval($this->phone_code))) {
            return [
                'code'=>1,
                'msg'=>'验证码不正确'
            ];
        }
        $user->binding = $this->phone;
        if ($user->save()) {
            \Yii::$app->cache->delete('code_cache'.$mobile);
            return [
                'code' => 0,
                'msg'=>'保存成功'
            ];
        } else {
            return [
                'code' => 1,
                'msg' => 'fail'
            ];
        }
    }

    public function binding()
    {
        $res = $this->getOpenid($this->code);
        if (strlen($res['session_key']) != 24) {
            return 1;
        }
        if (strlen($this->iv) != 24) {
            return 3;
        }
        require __DIR__ . '/wxbdc/WXBizDataCrypt.php';
        $pc = new WXBizDataCrypt($this->wechat_app->app_id, $res['session_key']);
        $errCode = $pc->decryptData($this->encryptedData, $this->iv, $data);
        if ($errCode == 0) {
            $dataObj = json_decode($data);
            $user = User::findOne(['id' => $this->user_id]);
            $user->binding = $dataObj->phoneNumber;
            $user->save();
            return [
                'code' => 0,
                'msg' => 'success',
                'data' => [
                    'dataObj' => $dataObj->phoneNumber,
                ]
            ];
        } else {
            return [
                'code'=>1,
                'msg'=>'授权失败',
                'data'=>$errCode
            ];
        }
    }

    private function getOpenid($code)
    {
        $api = "https://api.weixin.qq.com/sns/jscode2session?appid={$this->wechat_app->app_id}&secret={$this->wechat_app->app_secret}&js_code={$code}&grant_type=authorization_code";
        $curl = new Curl();
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->get($api);
        $res = $curl->response;
        $res = json_decode($res, true);
        return $res;
    }
}
