<?php

namespace app\models;

use Yii;
use Codeception\PHPUnit\ResultPrinter\HTML;

/**
 * This is the model class for table "{{%order_form}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $order_id
 * @property string $key
 * @property string $value
 * @property integer $is_delete
 * @property string $type
 */
class OrderForm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_form}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'order_id', 'is_delete'], 'integer'],
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
            'order_id' => 'Order ID',
            'key' => '表单key',
            'value' => '表单value',
            'is_delete' => 'Is Delete',
            'type' => '表单信息类型',
        ];
    }
    public function beforeSave($insert)
    {
        $this->value = \yii\helpers\Html::encode($this->value);
        return parent::beforeSave($insert);
    }
}
