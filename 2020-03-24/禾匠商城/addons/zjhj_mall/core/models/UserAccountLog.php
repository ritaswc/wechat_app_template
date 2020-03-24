<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%user_account_log}}".
 *
 * @property string $id
 * @property integer $user_id
 * @property integer $type
 * @property string $price
 * @property string $desc
 * @property integer $addtime
 * @property integer $order_type
 * @property integer $order_id
 */
class UserAccountLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_account_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'price', 'desc', 'addtime', 'order_type'], 'required'],
            [['user_id', 'type', 'addtime', 'order_type', 'order_id'], 'integer'],
            [['price'], 'number'],
            [['desc'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'type' => '类型：1=收入，2=支出',
            'price' => '变动金额',
            'desc' => '变动说明',
            'addtime' => 'Addtime',
            'order_type' => '订单类型 0--充值 1--商城订单 2--秒杀订单 3--拼团订单 4--商城订单退款 5--秒杀订单退款 6--拼团订单退款 7--后台改动',
            'order_id' => '订单ID',
        ];
    }
}
