<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%step_goods}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $goods_id
 * @property string $step_price
 * @property integer $create_time
 * @property string $attr
 * @property integer $status
 * @property integer $sort
 * @property integer $is_delete
 */
class StepGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%step_goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'goods_id', 'create_time', 'status', 'sort', 'is_delete'], 'integer'],
            [['step_price'], 'number'],
            [['attr', 'is_delete'], 'required'],
            [['attr'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'store_id' => 'Store ID',
            'goods_id' => 'Goods ID',
            'step_price' => '货币价',
            'create_time' => '创建时间',
            'attr' => '规格',
            'status' => '状态',
            'sort' => '排序',
            'is_delete' => 'Is Delete',
        ];
    }
}
