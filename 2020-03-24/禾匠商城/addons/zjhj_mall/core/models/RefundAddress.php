<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%refund_address}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $name
 * @property string $address
 * @property string $mobile
 * @property integer $is_delete
 */
class RefundAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%refund_address}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'is_delete'], 'integer'],
            [['name', 'address', 'mobile'], 'string', 'max' => 255],
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
            'name' => '收件人名称',
            'address' => '收件人地址',
            'mobile' => '收件人电话',
            'is_delete' => 'Is Delete',
        ];
    }
}
