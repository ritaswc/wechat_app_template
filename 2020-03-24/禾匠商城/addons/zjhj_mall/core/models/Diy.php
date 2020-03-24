<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%diy}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $parent_id
 * @property string $name
 * @property integer $type
 * @property string $value
 */
class Diy extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%diy}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'parent_id', 'type'], 'integer'],
            [['value'], 'required'],
            [['value'], 'string'],
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
            'parent_id' => '父级ID',
            'name' => '名称',
            'type' => '数据类型：0--模板 1--组件',
            'value' => 'json',
        ];
    }
}
