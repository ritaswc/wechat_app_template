<?php
/**
 * @copyright ©2018 浙江禾匠信息科技
 * @author Lu Wei
 * @link http://www.zjhejiang.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/6/22 11:55
 */


namespace app\modules\admin\models\password;

use app\models\Admin;
use app\modules\admin\models\AdminModel;

class ResetPasswordForm extends AdminModel
{
    public $admin_id;
    public $sms_code;
    public $password;
    private $mobile;

    public function rules()
    {
        return [
            [['sms_code',], 'trim'],
            [['admin_id', 'sms_code', 'password'], 'required'],
            [['sms_code',], function ($attribute, $params) {
                //校验用户验证短信验证码的次数
                $max_validate_count = 100;
                $validate_count = \Yii::$app->session->get(Password::RESET_PASSWORD_SMS_CODE_VALIDATE_COUNT, 0);
                if ($validate_count >= $max_validate_count) {
                    $this->addError($attribute, '您输入的短信验证码错误次数过多，请刷新页后面重试。');
                    return;
                }
            }],
            [['sms_code',], function ($attribute, $params) {
                //校验用户的短信验证码
                $data = \Yii::$app->session->get(Password::RESET_PASSWORD_SMS_CODE);
                if (!$data) {
                    $this->addError($attribute, '未查询到验证码发送记录，请刷新页后面重试。');
                    return;
                }
                $sms_code = $data['code'];
                $this->mobile = $data['mobile'];
                if (strval($this->attributes[$attribute]) !== strval($sms_code)) {
                    $validate_count = intval(\Yii::$app->session->get(Password::RESET_PASSWORD_SMS_CODE_VALIDATE_COUNT, 0));
                    \Yii::$app->session->set(Password::RESET_PASSWORD_SMS_CODE_VALIDATE_COUNT, $validate_count + 1);
                    $this->addError($attribute, '短信验证码错误。');
                    return;
                }
            }],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $admin = Admin::findOne([
            'id' => $this->admin_id,
            'mobile' => $this->mobile,
        ]);
        if (!$admin) {
            return [
                'code' => 1,
                'msg' => '所选账户与手机号不匹配，请刷新页后面重试。',
            ];
        }
        $admin->access_token = \Yii::$app->security->generateRandomString();
        $admin->auth_key = \Yii::$app->security->generateRandomString();
        $admin->password = \Yii::$app->security->generatePasswordHash($this->password, 5);
        if ($admin->save()) {
            \Yii::$app->session->remove(Password::RESET_PASSWORD_SMS_CODE);
            \Yii::$app->session->remove(Password::RESET_PASSWORD_SMS_CODE_VALIDATE_COUNT);
            return [
                'code' => 0,
                'msg' => '密码重置成功。',
            ];
        } else {
            return $this->getErrorResponse($admin);
        }
    }
}
