<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/1/31
 * Time: 11:29
 */

namespace app\modules\mch\models\fxhb;

use app\models\FxhbSetting;
use app\modules\mch\models\MchModel;

class FxhbSettingForm extends MchModel
{
    /** @var FxhbSetting $model */
    public $model;
    public $user_num;
    public $coupon_total_money;
    public $coupon_use_minimum;
    public $coupon_expire;
    public $distribute_type;
    public $game_time;
    public $game_open;
    public $rule;
    public $share_title;
    public $share_pic;

    public function rules()
    {
        $rs = [
            [['user_num', 'coupon_total_money', 'coupon_use_minimum', 'distribute_type', 'game_time', 'coupon_expire', 'rule', 'share_title', 'share_pic',], 'trim',],
            [['user_num'], 'integer', 'min' => 2, 'max' => 10000,],
            [['coupon_total_money'], 'number', 'min' => $this->user_num * 0.01,],
            [['coupon_use_minimum'], 'number', 'min' => 0,],
            [['game_time'], 'integer', 'min' => 1,],
            [['game_open'], 'integer',],
            ['user_num', 'default', 'value' => 2,],
            ['coupon_total_money', 'default', 'value' => $this->user_num * 0.01,],
            ['coupon_use_minimum', 'default', 'value' => 0,],
            ['game_time', 'default', 'value' => 24,],
            [['coupon_total_money','coupon_use_minimum'], 'number', 'max'=>99999999],
            [['coupon_expire','game_time'], 'integer', 'max' => 99999999,],
            ['coupon_expire', 'default', 'value' => 30,],
            [['user_num', 'coupon_total_money', 'coupon_use_minimum', 'distribute_type', 'game_time', 'coupon_expire',], 'required',],
        ];
        return $rs;
    }

    public function attributeLabels()
    {
        return [
            'user_num' => '红包人数',
            'coupon_total_money' => '红包总金额',
            'coupon_use_minimum' => '代金券门槛',
            'game_time' => '拆红包有效时间',
            'coupon_expire' => '代金券有效时间',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        if (!$this->coupon_total_money) {
            $this->coupon_total_money = $this->user_num * 0.01;
        }
        if ($this->coupon_total_money < $this->user_num * 0.01) {
            return [
                'code' => 1,
                'msg' => '红包总金额最低不能小于' . $this->user_num * 0.01,
            ];
        }
        $this->model->attributes = $this->attributes;
        if ($this->model->save()) {
            return [
                'code' => 0,
                'msg' => '保存成功',
            ];
        } else {
            return $this->getErrorResponse($this->model);
        }
    }
}
