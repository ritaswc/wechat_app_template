<?php
/**
 * @copyright ©2018 浙江禾匠信息科技
 * @author Lu Wei
 * @link http://www.zjhejiang.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/6/22 10:26
 */


namespace app\modules\admin\models\password;

use app\models\Admin;
use app\models\Option;
use app\modules\admin\models\AdminModel;
use Hejiang\Sms\Exceptions\SmsException;
use Hejiang\Sms\Messages\VerificationCodeMessage;
use Hejiang\Sms\Senders\AliyunSender;

class SendSmsCodeForm extends AdminModel
{
    public $mobile;
    public $captcha_code;


    public function rules()
    {
        return [
            [['mobile', 'captcha_code'], 'trim',],
            [['mobile', 'captcha_code'], 'required',],
            [['mobile',], 'match', 'pattern' => '/^1[3456789]{1}\d{9}$/',],
            [['captcha_code',], 'captcha', 'captchaAction' => 'admin/passport/sms-code-captcha',],
        ];
    }

    public function attributeLabels()
    {
        return [
            'mobile' => '手机号',
            'captcha_code' => '验证码',
        ];
    }

    public function send()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $exist_admin_list = Admin::find()->where([
            'mobile' => $this->mobile,
            'is_delete' => 0,
        ])->select('id,username')->asArray()->all();
        if (empty($exist_admin_list)) {
            return [
                'code' => 1,
                'msg' => '该手机号未绑定任何账户。',
            ];
        }
        $res = $this->senCode();
        if ($res !== true) {
            return $res;
        }
        return [
            'code' => 0,
            'msg' => '短信发送成功。',
            'data' => [
                'admin_list' => $exist_admin_list,
            ],
        ];
    }

    protected function senCode()
    {
        $sms_code = mt_rand(100000, 999999);
        $data = [
            'mobile' => $this->mobile,
            'code' => $sms_code,
        ];

        $ind_sms = Option::get('ind_sms', 0, 'admin');
        if (!$ind_sms) {
            return [
                'code' => 1,
                'msg' => '发送失败，短信尚未配置。',
            ];
        }
        if (!$ind_sms['aliyun'] || !$ind_sms['aliyun']['access_key_id'] || !$ind_sms['aliyun']['access_key_secret'] || !$ind_sms['aliyun']['sign'] || !$ind_sms['aliyun']['tpl_id']) {
            return [
                'code' => 1,
                'msg' => '发送失败，短信尚未配置。',
            ];
        }
        $sender = new AliyunSender($ind_sms['aliyun']['access_key_id'], $ind_sms['aliyun']['access_key_secret']);
        $message = new VerificationCodeMessage([
            'sender' => $sender,
            'sign' => $ind_sms['aliyun']['sign'],
            'tplId' => $ind_sms['aliyun']['tpl_id'],
            'tplParams' => [
                'code' => $sms_code,
            ],
            'phoneNumber' => $this->mobile,
        ]);
        try {
            $message->send();
        } catch (SmsException $e) {
            return [
                'code' => 1,
                'msg' => '发送失败,' . $e->getMessage(),
            ];
        }
        \Yii::$app->session->set(Password::RESET_PASSWORD_SMS_CODE, $data);
        \Yii::$app->session->set(Password::RESET_PASSWORD_SMS_CODE_VALIDATE_COUNT, 0);
        return true;
    }
}
