<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%order_express}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $order_id
 * @property integer $order_type
 * @property string $express_code
 * @property string $EBusinessID
 * @property string $order
 * @property string $printTeplate
 * @property integer $is_delete
 */
class OrderExpress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_express}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'order_id', 'order_type', 'is_delete'], 'integer'],
            [['order', 'printTeplate'], 'string'],
            [['express_code', 'EBusinessID'], 'string', 'max' => 255],
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
            'order_type' => '订单类型 0--商城订单 1--秒杀订单 2--拼团订单 ',
            'express_code' => '快递公司编码',
            'EBusinessID' => '快递鸟id',
            'order' => 'Order',
            'printTeplate' => 'Print Teplate',
            'is_delete' => 'Is Delete',
        ];
    }
}
