<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%bargain_goods}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $goods_id
 * @property string $min_price
 * @property integer $begin_time
 * @property integer $end_time
 * @property integer $time
 * @property integer $status
 * @property string $status_data
 * @property integer $is_delete
 * @property integer $addtime
 */
class BargainGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bargain_goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'goods_id', 'begin_time', 'end_time', 'time', 'status', 'is_delete', 'addtime'], 'integer'],
            [['min_price'], 'number'],
            [['status_data'], 'string', 'max' => 255],
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
            'goods_id' => 'Goods ID',
            'min_price' => '最低价',
            'begin_time' => '活动开始时间',
            'end_time' => '活动结束时间',
            'time' => '砍价小时数',
            'status' => '砍价方式 0--按人数 1--按价格',
            'status_data' => '砍价方式数据',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
        ];
    }

    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'goods_id']);
    }

    // 根据指定商品ID获取商品信息和砍价信息
    public static function getIdToGoods($goods_id,$store)
    {
        $goods = Goods::find()->with(['bargain'])->where([
            'is_delete' => 0, 'id' => $goods_id,
            'store_id' => $store->id
        ])->one();
        return $goods;
    }

    public function getBeginTimeText()
    {
        return $this->begin_time ? date('Y-m-d H:i',$this->begin_time) : date('Y-m-d H:i',time());
    }

    public function getEndTimeText()
    {
        return $this->end_time ? date('Y-m-d H:i',$this->end_time) : "";
    }
}
