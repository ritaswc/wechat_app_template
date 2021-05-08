<?php

namespace app\models; 

use Yii; 

/** 
 * This is the model class for table "{{%scratch_log}}". 
 * 
 * @property integer $id
 * @property integer $store_id
 * @property integer $pond_id
 * @property integer $user_id
 * @property integer $status
 * @property integer $create_time
 * @property integer $raffle_time
 * @property integer $type
 * @property integer $num
 * @property integer $gift_id
 * @property integer $coupon_id
 * @property string $price
 * @property string $attr
 * @property integer $order_id
 */ 
class ScratchLog extends \yii\db\ActiveRecord
{ 
    /** 
     * @inheritdoc 
     */ 
    public static function tableName() 
    { 
        return '{{%scratch_log}}'; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function rules() 
    { 
        return [
            [['store_id', 'pond_id', 'user_id', 'status', 'create_time', 'raffle_time', 'type', 'num', 'gift_id', 'coupon_id', 'order_id'], 'integer'],
            [['price'], 'number'],
            [['attr'], 'string', 'max' => 255],
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
            'pond_id' => 'Pond ID',
            'user_id' => 'User ID',
            'status' => ' 0预领取 1 未领取 2 已领取',
            'create_time' => '创建时间',
            'raffle_time' => '领取时间',
            'type' => 'Type',
            'num' => 'Num',
            'gift_id' => 'Gift ID',
            'coupon_id' => 'Coupon ID',
            'price' => 'Price',
            'attr' => '规格',
            'order_id' => '订单号',
        ]; 
    } 
    public function getCoupon()
    {
        return $this->hasOne(Coupon::className(), ['id' => 'coupon_id']);
    }

    public function getGift()
    {
        return $this->hasOne(Goods::className(), ['id' => 'gift_id']);
    }
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->alias('user');
    }
}
