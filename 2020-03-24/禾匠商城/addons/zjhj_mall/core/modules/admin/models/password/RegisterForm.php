<?php
/**
 * @copyright ©2018 Lu Wei
 * @author Lu Wei
 * @link http://www.luweiss.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/7/12 16:15
 */


namespace app\modules\admin\models\password;

use app\models\Admin;
use app\models\AdminRegister;
use app\modules\admin\models\AdminModel;

class RegisterForm extends AdminModel
{
    public $username;
    public $password;
    public $password2;
    public $name;
    public $mobile;
    public $sms_code;
    public $desc;

    public $post_attrs;

    public function rules()
    {
        return [
            [['username', 'name', 'mobile', 'sms_code', 'desc'], 'trim'],
            [['username', 'name', 'mobile', 'password', 'password2'], 'required'],
            ['username', 'match', 'pattern' => '/^[a-z]([a-z|0-9|_])*$/i', 'message' => '帐户名必须是字母开头，只允许有字母、数字、下划线 _ 。'],
            ['username', function ($attribute, $params) {
                $exist_admin = Admin::find()->where([
                    'username' => $this->username,
                    'is_delete' => 0,
                ])->exists();
                if ($exist_admin) {
                    $this->addError($attribute, "帐户名{$this->username}已经被使用，修改帐户名。");
                    return;
                }
                $exist_admin_register = AdminRegister::find()->where([
                    'username' => $this->username,
                    'is_delete' => 0,
                    'status' => 0,
                ])->exists();
                if ($exist_admin_register) {
                    $this->addError($attribute, "帐户名{$this->username}已经被使用，修改帐户名。");
                    return;
                }
            }],
            ['password', 'compare', 'compareAttribute' => 'password2', 'message' => '两次密码输入不一致。'],
            //['name', 'match', 'pattern' => '/^[a-z]([a-z|0-9|_])*$/i', 'message' => '姓名/企业名只允许有中文、字母。'],
            ['name', 'match', 'pattern' => '/^[\x80-\xff_\w()（）]{1,64}$/u', 'message' => '姓名/企业名只允许有中文、字母。'],
            ['mobile', 'match', 'pattern' => '/^1[0-9]{10}$/i', 'message' => '手机号码格式不正确。'],
            ['sms_code', 'required'],
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

    public function attributeLabels()
    {
        return [
            'username' => '帐户名',
            'password' => '密码',
            'password2' => '确认密码',
            'name' => '姓名/企业名称',
            'mobile' => '手机号',
            'sms_code' => '验证码',
            'desc' => '申请理由',
        ];
    }

    public function validateAttr()
    {
        $attrs = [];
        if (is_array($this->post_attrs)) {
            foreach ($this->post_attrs as $a => $v) {
                $attrs[] = $a;
            }
        }
        if (!$this->validate($attrs)) {
            return $this->getErrorResponse();
        }
        return [
            'code' => 0,
            'data' => $attrs,
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $admin_register = new AdminRegister();
        $admin_register->attributes = $this->attributes;
        $admin_register->password = \Yii::$app->security->generatePasswordHash($this->password);
        $admin_register->addtime = time();
        $admin_register->status = 0;
        $admin_register->is_delete = 0;
        if ($admin_register->save()) {
            return [
                'code' => 0,
            ];
        } else {
            return $this->getErrorResponse($admin_register);
        }
    }
}
