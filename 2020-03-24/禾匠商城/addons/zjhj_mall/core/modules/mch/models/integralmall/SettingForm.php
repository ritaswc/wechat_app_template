<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/5/29
 * Time: 14:18
 */

namespace app\modules\mch\models\integralmall;

use app\modules\mch\models\MchModel;

class SettingForm extends MchModel
{
    public $store_id;
    public $integral_shuoming;
    public $register_rule;
    public $register_integral;
    public $register_continuation;
    public $register_reward;
    public $setting;
    public $attr;

    public function rules()
    {
        return [
            [['store_id', 'integral_shuoming', 'register_rule', 'register_integral','register_continuation', 'register_reward'], 'required'],
            [[ 'register_integral', 'register_continuation','register_reward'], 'integer','min'=>0,'max'=>999999],
            [['register_rule'], 'string'],
            [['integral_shuoming'], 'safe'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'store_id' => 'Store ID',
            'integral_shuoming' => '积分说明',
            'register_rule' => '签到规则',
            'register_integral' => '签到获得分数',
            'register_continuation' => '连续签到天数',
            'register_reward' => '连续签到奖励',
        ];
    }
    public function save()
    {
        if ($this->validate()) {
            $setting = $this->setting;
            $old_attr = json_decode($setting->integral_shuoming,true);
            if (!$old_attr) {
                $old_attr = [];
            }
            $new_attr = json_decode($this->integral_shuoming,true);
            if (!$new_attr) {
                $new_attr = [];
            }
            $attr = array_merge($old_attr, $new_attr);
            $setting->store_id = $this->store_id;
            $setting->integral_shuoming = \Yii::$app->serializer->encode($attr);
            $setting->register_rule = $this->register_rule;
            $setting->register_integral = $this->register_integral;
            $setting->register_continuation = $this->register_continuation;
            $setting->register_reward = $this->register_reward;
            if ($setting->save()) {
                return [
                    'code' => 0,
                    'msg' => '成功',
                ];
            }
        } else {
            return $this->errorResponse;
        }
    }
}
