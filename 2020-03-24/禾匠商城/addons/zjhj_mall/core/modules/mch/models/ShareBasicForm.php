<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/8
 * Time: 18:30
 */

namespace app\modules\mch\models;

use app\models\Option;

/**
 * @property \app\models\Setting $list;
 */
class ShareBasicForm extends MchModel
{
    public $list;
    public $store_id;

    public $level;
    public $condition;
    public $share_condition;
    public $content;
    public $pay_type;
    public $bank;
    public $agree;
    public $min_money;
    public $pic_url_1;
    public $pic_url_2;
    public $cash_success_tpl;
    public $cash_fail_tpl;
    public $apply_tpl;
    public $remaining_sum;

    public $cash_max_day, $auto_share_val;
    public $is_rebate;
    public $cash_service_charge;
    public $share_good_status;
    public $share_good_id;

    public function rules()
    {
        return [
            [['level', 'condition', 'share_condition','remaining_sum','is_rebate', 'share_good_status', 'share_good_id'], 'integer'],
            [['content', 'agree', 'pic_url_1', 'pic_url_2', 'cash_success_tpl', 'cash_fail_tpl','apply_tpl'], 'trim'],
            [['min_money'], 'number', 'min' => 1, 'max' => 999999],
            [['min_money'], 'default', 'value' => 1],
            [['pay_type'], 'required'],
            [['cash_max_day', 'cash_service_charge'], 'default', 'value' => 0],
            [['cash_max_day', 'cash_service_charge'], 'number', 'min' => 0],
            [['auto_share_val'], 'default', 'value' => 0],
            [['auto_share_val'], 'number', 'min' => 0],
        ];
    }

    public function attributeLabels()
    {
        return [
            'min_money' => '最小提现金额',
            'content' => '用户须知',
            'agree' => '申请协议',
            'pay_type' => '提现方式',
            'bank' => '银行卡支付',
            'cash_max_day' => '每日提现上限',
            'auto_share_val' => '消费自动成为分销商',
            'remaining_sum' => '余额提现',
            'is_rebate' => '是否开启分销自购返利',
            'cash_service_charge' => '提现手续费',
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $list = $this->list;
            if ($list->isNewRecord) {
                $list->store_id = $this->store_id;
                $list->first = 0;
                $list->second = 0;
                $list->third = 0;
                $list->first_name = '一级';
                $list->second_name = '二级';
                $list->third_name = '三级';
            }
            $list->level = $this->level;
            $list->condition = $this->condition;
            $list->content = $this->content;
            $list->share_condition = $this->share_condition;
            $list->agree = $this->agree;
            $list->min_money = $this->min_money;
            $list->pic_url_1 = $this->pic_url_1;
            $list->pic_url_2 = $this->pic_url_2;
            $list->is_rebate = $this->is_rebate;
            $list->share_good_status = $this->share_good_status;
            $list->share_good_id = $this->share_good_id ? $this->share_good_id : 0;
            $pay_type = $this->pay_type;
            isset($pay_type['bank'])? $list->bank = 1:$list->bank = 0;
            isset($pay_type['remaining_sum'])? $list->remaining_sum = 1:$list->remaining_sum = 0;

            if (isset($pay_type['wechat']) && !isset($pay_type['alipay'])) {
                $list->pay_type = 0;
            }
            if (isset($pay_type['alipay']) && !isset($pay_type['wechat'])) {
                $list->pay_type = 1;
            }
            if (isset($pay_type['wechat']) && isset($pay_type['alipay'])) {
                $list->pay_type = 2;
            }
            if (!isset($pay_type['wechat']) && !isset($pay_type['alipay'])) {
                $list->pay_type = 3;
            }
            // TODO 不知道这两个参数是什么意思，这段代码应移到模版消息管理代码中
            Option::setList([
                [
                    'name' => 'cash_max_day',
                    'value' => number_format($this->cash_max_day, 2, '.', ''),
                ],
                [
                    'name' => 'auto_share_val',
                    'value' => number_format($this->auto_share_val, 2, '.', ''),
                ],
                [
                    'name' => 'cash_service_charge',
                    'value' => number_format($this->cash_service_charge, 2, '.', ''),
                ]
            ], $this->store_id, 'share');

            if ($list->save()) {
                return [
                    'code' => 0,
                    'msg' => '成功'
                ];
            } else {
                return [
                    'code' => 1,
                    'msg' => '失败',
                    'data' => $list->errors
                ];
            }
        } else {
            return $this->errorResponse;
        }
    }
}
