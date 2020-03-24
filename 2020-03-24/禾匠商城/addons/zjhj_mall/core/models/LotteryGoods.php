<?php

namespace app\models; 

use Yii; 

/** 
 * This is the model class for table "{{%lottery_goods}}". 
 * 
 * @property integer $id
 * @property integer $store_id
 * @property integer $goods_id
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $stock
 * @property string $attr
 * @property integer $is_delete
 * @property integer $sort
 * @property integer $status
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $type
 */ 
class LotteryGoods extends \yii\db\ActiveRecord
{ 
    /** 
     * @inheritdoc 
     */ 
    public static function tableName() 
    { 
        return '{{%lottery_goods}}'; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function rules() 
    { 
        return [
            [['store_id', 'goods_id', 'start_time', 'end_time', 'stock', 'is_delete', 'sort', 'status', 'create_time', 'update_time', 'type'], 'integer'],
            [['attr'], 'required'],
            [['attr'], 'string'],
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
            'goods_id' => '商品id',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'stock' => '数量',
            'attr' => '规格',
            'is_delete' => 'Is Delete',
            'sort' => '排序',
            'status' => '是否关闭',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
            'type' => '0未完成 1已完成',
        ]; 
    } 

    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }

    public function getLog()
    {
        return $this->hasMany(LotteryLog::className(), ['lottery_id' => 'id']);
    }
}
