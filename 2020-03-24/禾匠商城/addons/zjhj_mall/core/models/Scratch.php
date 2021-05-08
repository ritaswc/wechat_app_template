<?php

namespace app\models; 

use Yii; 

/** 
 * This is the model class for table "{{%scratch}}". 
 * 
 * @property integer $id
 * @property integer $store_id
 * @property integer $type
 * @property integer $num
 * @property string $price
 * @property integer $stock
 * @property integer $coupon_id
 * @property integer $gift_id
 * @property integer $create_time
 * @property integer $update_time
 * @property string $attr
 * @property integer $status
 * @property integer $is_delete
 */ 
class Scratch extends \yii\db\ActiveRecord
{ 
    /** 
     * @inheritdoc 
     */ 
    public static function tableName() 
    { 
        return '{{%scratch}}'; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function rules() 
    { 
        return [
            [['store_id'], 'required'],
            [['store_id', 'type', 'num', 'stock', 'coupon_id', 'gift_id', 'create_time', 'update_time', 'status', 'is_delete'], 'integer'],
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
            'type' => '1.红包2.优惠卷3.积分4.实物',
            'num' => '积分数量',
            'price' => '红包价格',
            'stock' => '库存',
            'coupon_id' => '优惠卷',
            'gift_id' => '赠品',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'attr' => '规格',
            'status' => '0关闭 1开启',
            'is_delete' => '删除',
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
}
