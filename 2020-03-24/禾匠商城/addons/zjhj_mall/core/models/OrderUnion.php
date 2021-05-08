<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%order_union}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property string $order_no
 * @property string $order_id_list
 * @property string $price
 * @property integer $is_pay
 * @property integer $addtime
 * @property integer $is_delete
 */
class OrderUnion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_union}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id', 'order_no', 'order_id_list', 'price'], 'required'],
            [['store_id', 'user_id', 'is_pay', 'addtime', 'is_delete'], 'integer'],
            [['order_id_list'], 'string'],
            [['price'], 'number'],
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
            'user_id' => 'User ID',
            'order_no' => '支付单号',
            'order_id_list' => '订单id列表',
            'price' => '支付金额',
            'is_pay' => '是否已付款0=未付款，1=已付款',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(),['id'=>'user_id']);
    }
}
