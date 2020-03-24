<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\models;

use app\models\common\admin\log\CommonActionLog;
use app\models\Model;
use app\models\User;
use Yii;

class MchLoginForm extends MchModel
{
    public $username;
    public $store_id;
    public $password;
    public $captcha_code;

    public function rules()
    {
        return [
            [['username', 'captcha_code'], 'trim'],
            [['username', 'captcha_code', 'password', 'store_id'], 'required'],
            [['captcha_code',], 'captcha', 'captchaAction' => 'mch/permission/passport/captcha',],
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

        $admin = User::findOne([
            'username' => $this->username,
            'type' => User::USER_TYPE_ROLE,
            'store_id' => $this->store_id,
            'is_delete' => Model::IS_DELETE_FALSE
        ]);
        if (!$admin) {
            return [
                'code' => 1,
                'msg' => '账号不存在',
            ];
        }
        if ($admin->is_delete) {
            return [
                'code' => 1,
                'msg' => '账号已被删除，请联系管理员'
            ];
        }
        if (!\Yii::$app->security->validatePassword($this->password, $admin->password)) {
            return [
                'code' => 1,
                'msg' => '用户名或密码错误',
            ];
        }

        //清除其它类型登录的认证
        if (Yii::$app->admin->isGuest == false) {
            Yii::$app->admin->logout();
        }
        if (Yii::$app->user->isGuest == false) {
            Yii::$app->user->logout();
        }
        Yii::$app->mchRoleAdmin->login($admin);
        Yii::$app->session->set('store_id', $admin->store_id);

        CommonActionLog::storeActionLog('', 'login', 0, [], $admin->id);

        return [
            'code' => 0,
            'msg' => '登录成功',
        ];
    }
}
