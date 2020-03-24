<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%free_delivery_rules}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $price
 * @property string $city
 */
class FreeDeliveryRules extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%free_delivery_rules}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id'], 'required'],
            [['store_id'], 'integer'],
            [['price'], 'number'],
            [['city'], 'string'],
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
            'price' => 'Price',
            'city' => 'City',
        ];
    }
}
