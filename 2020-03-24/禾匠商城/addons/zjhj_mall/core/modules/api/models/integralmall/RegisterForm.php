<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/5/29
 * Time: 21:55
 */

namespace app\modules\api\models\integralmall;

use app\models\IntegralSetting;
use app\models\User;
use app\modules\api\models\ApiModel;
use app\models\Register;

class RegisterForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $register_time;

    public function rules()
    {
        return [
            [['store_id', 'user_id', 'register_time'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'store_id' => 'Store ID',
            'user_id' => 'User ID',
            'register_time' => '签到日期',
        ];
    }

    public function save()
    {
        $register = new Register();
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $first_day = Register::find()->where(['store_id' => $this->store_id, 'user_id' => $this->user_id, 'type' => 1])->orderBy('addtime DESC')->asArray()->one();
        $setting = IntegralSetting::find()->where(['store_id' => $this->store_id])->asArray()->one();
        $user = User::findOne(['id' => $this->user_id, 'store_id' => $this->store_id]);
        if (!$setting) {
            return [
                'code' => 1,
                'msg' => '签到未设置'
            ];
        }
        $day1 = $first_day['continuation'];
        $first_day['register_time'] = strtotime(date('Ymd', strtotime($first_day['register_time'])));
//        $first_day['register_time'] = explode("/",$first_day['register_time']);
//        $first_day['register_time'] = implode("", $first_day['register_time']);
        $date = strtotime(date('Ymd', time()));
        $day = $date - $first_day['register_time'];
        if ($date == $first_day['register_time']) {
            return [
                'code' => 1,
                'msg' => '已签到'
            ];
        }
        $day = ($date - $first_day['register_time']) / 86400;
        if ($day >= 1 && $day < 2) {
            $day1++;
        } else {
            $day1 = 1;
        }
        $score = $setting['register_integral'];
        if ($day1 >= $setting['register_continuation']) {
            $score = $setting['register_reward'] + $setting['register_integral'];
        } else {
            $score = $setting['register_integral'];
        }
        $register->store_id = $this->store_id;
        $register->user_id = $this->user_id;
        $register->register_time = $this->register_time;
        $register->addtime = time();
        $register->continuation = $day1;
        $register->integral = $score;
        $register->type = 1;
        $user->integral += $score;
        if ($user->save()) {
            if ($register->save()) {
                return [
                    'code' => 0,
                    'msg' => '签到成功',
                    'data' => [
                        'continuation' => $register->continuation
                    ]
                ];
            } else {
                return $this->getErrorResponse($register);
            }
        } else {
            return $this->getErrorResponse($user);
        }
    }
}
