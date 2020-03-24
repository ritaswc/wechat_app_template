<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%register}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property string $register_time
 * @property integer $addtime
 * @property integer $continuation
 * @property integer $integral
 * @property integer $type
 * @property integer $order_id
 */
class Register extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%register}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id', 'register_time'], 'required'],
            [['store_id', 'user_id', 'addtime','continuation','integral','type','order_id'], 'integer'],
            [['register_time'], 'string', 'max' => 25],
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
            'user_id' => 'User ID',
            'register_time' => 'Register Time',
            'addtime' => 'Addtime',
            'continuation' => 'continuation',
            'integral' => 'integral',
            'type' => '类型 1--签到 3--后台充值或扣除 4--商城购物抵扣 5--秒杀抵扣 6--商城取消恢复 7--商城购物获得 8--秒杀获得 9--售后积分恢复 10--积分兑换优惠券 11--积分兑换商品抵扣 12--积分商品取消 13--秒杀退还',
            'order_id' => 'order_id',
        ];
    }

    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id'=>'order_id']);
    }

    public function getMsOrder()
    {
        return $this->hasOne(MsOrder::className(), ['id'=>'order_id']);
    }

    public function getUserCoupon()
    {
        return $this->hasOne(UserCoupon::className(), ['id'=>'order_id']);
    }

    public function getIntegralOrderDetail()
    {
        return $this->hasOne(IntegralOrderDetail::className(), ['order_id'=>'order_id']);
    }
}
