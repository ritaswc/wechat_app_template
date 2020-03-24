<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%bargain_user_order}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $order_id
 * @property integer $user_id
 * @property string $price
 * @property integer $is_delete
 * @property integer $addtime
 */
class BargainUserOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bargain_user_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'order_id', 'user_id', 'is_delete', 'addtime'], 'integer'],
            [['price'], 'number'],
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
            'order_id' => 'Order ID',
            'user_id' => 'User ID',
            'price' => 'Price',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    // 获取指定订单id的砍价参与人数
    public static function getUserCount($store, $order_id)
    {
        return BargainUserOrder::find()->where([
            'is_delete' => 0,
            'store_id' => $store->id,
            'order_id' => $order_id
        ])->count();
    }

    // 获取指定订单id的已砍价金额
    public static function getPriceCount($store_id, $order_id)
    {
        $totalCount = BargainUserOrder::find()->where([
            'store_id' => $store_id,
            'order_id' => $order_id,
            'is_delete' => 0
        ])->select('sum(price)')->scalar();
        if(!$totalCount){
            $totalCount = 0;
        }
        return $totalCount;
    }
}
