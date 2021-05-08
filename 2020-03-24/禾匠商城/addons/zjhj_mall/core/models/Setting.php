<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%setting}}".
 *
 * @property integer $id
 * @property string $first
 * @property string $second
 * @property string $third
 * @property int $store_id
 * @property int $level
 * @property int $condition
 * @property int $share_condition
 * @property string $content
 * @property integer $pay_type
 * @property integer $min_money
 * @property string $agree
 * @property string $first_name
 * @property string $second_name
 * @property string $third_name
 * @property string $pic_url_1
 * @property string $pic_url_2
 * @property integer $price_type
 * @property integer $bank
 * @property integer $remaining_sum
 * @property string $rebate
 * @property integer $is_rebate
 */
class Setting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%setting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first', 'second', 'third', 'min_money', 'rebate'], 'number'],
            [['store_id', 'level', 'condition', 'share_condition', 'pay_type', 'price_type', 'bank', 'remaining_sum', 'is_rebate'], 'integer'],
            [['content', 'agree', 'pic_url_1', 'pic_url_2'], 'string'],
            [['first_name', 'second_name', 'third_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first' => 'First',
            'second' => 'Second',
            'third' => 'Third',
            'store_id' => '商城id',
            'level' => '分销层级 0--不开启 1--一级分销 2--二级分销 3--三级分销',
            'condition' => '成为下线条件 0--首次点击分享链接 1--首次下单 2--首次付款',
            'share_condition' => '成为分销商的条件 0--无条件 1--申请',
            'content'=>'分销佣金 的 用户须知',
            'pay_type'=>'提现方式 0--微信企业支付 1--支付宝转账',
            'min_money'=>'最小提现额度',
            'agree'=>'分销协议',
            'first_name' => '一级名称',
            'second_name' => '二级名称',
            'third_name' => '三级名称',
            'pic_url_1' => 'Pic Url 1',
            'pic_url_2' => 'Pic Url 2',
            'price_type' => '分销金额 0--百分比金额  1--固定金额',
            'bank' => 'Bank',
            'remaining_sum' => '余额提现',
            'rebate' => '分销返利',
            'is_rebate' => '是否开启自购返利',
        ];
    }
}
