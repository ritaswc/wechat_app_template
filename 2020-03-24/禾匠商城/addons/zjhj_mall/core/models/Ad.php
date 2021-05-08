<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%ad}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $is_delete
 * @property integer $status
 * @property integer $unit_id
 * @property integer $type
 * @property integer $create_time
 */
class Ad extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ad}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'is_delete', 'status', 'create_time', 'type'], 'integer'],
            [['create_time'], 'required'],
            [['unit_id'], 'string', 'max' => 255],
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
            'is_delete' => 'Is Delete',
            'status' => '0关闭 1开启',
            'unit_id' => '广告id',
            'create_time' => '创建时间',
            'type' => '类型'
        ];
    }
}
