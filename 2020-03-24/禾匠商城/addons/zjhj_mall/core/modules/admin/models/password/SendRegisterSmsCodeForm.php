<?php
/**
 * @copyright ©2018 浙江禾匠信息科技
 * @author Lu Wei
 * @link http://www.zjhejiang.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/7/11 16:58
 */


namespace app\modules\admin\models\password;

use app\models\Admin;
use app\models\AdminRegister;

class SendRegisterSmsCodeForm extends SendSmsCodeForm
{

    public function attributeLabels()
    {
        return [
            'mobile' => '手机号',
            'captcha_code' => '图片验证码',
        ];
    }

    public function send()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $exist_admin = Admin::find()->where([
            'mobile' => $this->mobile,
            'is_delete' => 0,
        ])->exists();
        if ($exist_admin) {
            return [
                'code' => 1,
                'msg' => '该手机号已经被使用，请更换其它手机号。',
            ];
        }
        $exist_admin_register = AdminRegister::find()->where([
            'mobile' => $this->mobile,
            'status' => 0,
            'is_delete' => 0,
        ])->exists();
        if ($exist_admin_register) {
            return [
                'code' => 1,
                'msg' => '该手机号的注册账户正在审核中。',
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
                'timeout' => 60,
            ],
        ];
    }
}
