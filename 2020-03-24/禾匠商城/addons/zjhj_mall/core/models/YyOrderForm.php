<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%yy_order_form}}".
 *
 * @property string $id
 * @property string $store_id
 * @property string $goods_id
 * @property string $user_id
 * @property string $order_id
 * @property string $key
 * @property string $value
 * @property string $is_delete
 * @property string $addtime
 * @property string $type
 */
class YyOrderForm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%yy_order_form}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'goods_id', 'user_id', 'order_id', 'is_delete', 'addtime'], 'integer'],
            [['key', 'value', 'type'], 'string', 'max' => 255],
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
            'user_id' => 'User ID',
            'order_id' => 'Order ID',
            'key' => 'Key',
            'value' => 'Value',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
            'type' => 'Type',
        ];
    }
}
