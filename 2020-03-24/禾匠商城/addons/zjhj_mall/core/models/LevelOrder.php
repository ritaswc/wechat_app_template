<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%level_order}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $order_no
 * @property integer $user_id
 * @property string $pay_price
 * @property integer $pay_type
 * @property integer $is_pay
 * @property integer $pay_time
 * @property integer $is_delete
 * @property integer $addtime
 * @property integer $current_level
 * @property integer $after_level
 */
class LevelOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%level_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id', 'pay_type', 'is_pay', 'pay_time', 'is_delete', 'addtime'], 'integer'],
            [['pay_price', 'current_level','after_level'], 'number'],
            [['order_no'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'order_no' => '订单号',
            'user_id' => '用户',
            'pay_price' => '支付金额',
            'pay_type' => '支付方式 1--微信支付',
            'is_pay' => '是否支付 0--未支付 1--支付',
            'pay_time' => '支付时间',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
            'current_level' => '购买后等级',
            'after_level' => '购买前等级',
        ];
    }
}
