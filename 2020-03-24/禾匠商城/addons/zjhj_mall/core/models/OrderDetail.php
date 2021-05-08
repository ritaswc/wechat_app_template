<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%order_detail}}".
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
 * @property string $integral
 * @property integer $is_level
 */
class OrderDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'goods_id', 'attr', 'pic'], 'required'],
            [['order_id', 'goods_id', 'num', 'addtime', 'is_delete', 'integral', 'is_level'], 'integer'],
            [['total_price'], 'number'],
            [['attr'], 'string'],
            [['pic'], 'string', 'max' => 255],
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
            'num' => '商品数量',
            'total_price' => '此商品的总价',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
            'attr' => '商品规格',
            'pic' => '商品规格图片',
            'integral' => '获取积分',
            'is_level' => '会员折扣',
        ];
    }

    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id'=>'goods_id']);
    }

    public function getOrder() {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}
