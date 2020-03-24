<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hjmall_gwd_buy_list".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $user_id
 * @property integer $store_id
 * @property integer $is_delete
 * @property integer $type
 * @property string $addtime
 */
class GwdBuyList extends \yii\db\ActiveRecord
{
    const TYPE_STORE = 0;
    const TYPE_MS = 1;
    const TYPE_PT = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gwd_buy_list}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'user_id', 'store_id', 'type'], 'required'],
            [['order_id', 'user_id', 'is_delete', 'store_id', 'type'], 'integer'],
            [['addtime'], 'string', 'max' => 255],
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
            'user_id' => 'User ID',
            'store_id' => 'Store ID',
            'type' => 'Type',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
        ];
    }
}
