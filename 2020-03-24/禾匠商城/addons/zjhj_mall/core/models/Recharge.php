<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%recharge}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $pay_price
 * @property string $send_price
 * @property string $name
 * @property integer $is_delete
 * @property integer $addtime
 */
class Recharge extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%recharge}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'is_delete', 'addtime'], 'integer'],
            [['pay_price', 'send_price'], 'number'],
            [['name'], 'string', 'max' => 255],
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
            'pay_price' => '支付金额',
            'send_price' => '赠送金额',
            'name' => '充值名称',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
        ];
    }
}
