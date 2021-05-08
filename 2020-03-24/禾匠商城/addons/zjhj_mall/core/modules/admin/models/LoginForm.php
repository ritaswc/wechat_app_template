<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/2
 * Time: 16:02
 */

namespace app\modules\admin\models;

use app\models\Admin;
use Yii;

class LoginForm extends AdminModel
{
    public $username;
    public $password;
    public $captcha_code;

    public function rules()
    {
        return [
            [['username', 'captcha_code'], 'trim'],
            [['username', 'captcha_code', 'password'], 'required'],
            [['captcha_code',], 'captcha', 'captchaAction' => 'admin/passport/captcha',],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'captcha_code' => '图片验证码',
        ];
    }

    public function login()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $admin = Admin::findOne([
            'username' => $this->username,
            'is_delete' => 0,
        ]);
        if (!$admin) {
            return [
                'code' => 1,
                'msg' => '用户名或密码错误',
            ];
        }
        if (!\Yii::$app->security->validatePassword($this->password, $admin->password)) {
            return [
                'code' => 1,
                'msg' => '用户名或密码错误',
            ];
        }
        Yii::$app->admin->login($admin);

        if (Yii::$app->mchRoleAdmin->isGuest == false) {
            Yii::$app->mchRoleAdmin->logout();
        }

        return [
            'code' => 0,
            'msg' => '登录成功',
        ];
    }
}
