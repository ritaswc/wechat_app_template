<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%miaosha}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $open_time
 */
class Miaosha extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%miaosha}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id'], 'required'],
            [['store_id'], 'integer'],
            [['open_time'], 'string'],
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
            'open_time' => '开放时间，JSON格式',
        ];
    }
}
