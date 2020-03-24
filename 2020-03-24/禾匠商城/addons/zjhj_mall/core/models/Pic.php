<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%pic}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $type
 * @property string $pic_url
 * @property integer $is_delete
 */
class Pic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pic}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'type', 'is_delete'], 'integer'],
            [['type'], 'required'],
            [['pic_url'], 'string', 'max' => 2048],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => '0 布数海报',
            'type' => 'Type',
            'pic_url' => 'Pic Url',
            'is_delete' => '是否删除',
        ];
    }
}
