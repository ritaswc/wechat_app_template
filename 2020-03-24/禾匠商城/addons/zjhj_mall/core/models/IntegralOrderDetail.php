<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%integral_order_detail}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $goods_id
 * @property integer $num
 * @property string $total_price
 * @property integer $addtime
 * @property integer $is_delete
 * @property string $attr
 * @property string $pic
 * @property string $pay_integral
 * @property string $goods_name
 * @property string $user_id
 * @property string $store_id
 */
class IntegralOrderDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%integral_order_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'goods_id', 'attr', 'pic'], 'required'],
            [['order_id', 'goods_id', 'num', 'addtime', 'is_delete', 'pay_integral','user_id','store_id'], 'integer'],
            [['total_price'], 'number'],
            [['attr'], 'string'],
            [['pic','goods_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'goods_id' => 'Goods ID',
            'num' => 'Num',
            'total_price' => 'Total Price',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
            'attr' => 'Attr',
            'pic' => 'Pic',
            'pay_integral' => 'Pay Integral',
            'goods_name' => 'goods_name',
            'user_id' => 'user_id',
            'store_id' => 'store_id',
        ];
    }

    public function getOrder()
    {
        return $this->hasOne(IntegralOrder::className(), ['id'=>'order_id']);
    }

    public function getGoods()
    {
        return $this->hasOne(IntegralGoods::className(), ['id'=>'goods_id']);
    }
}
