<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%file_group}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $name
 * @property integer $is_default
 * @property integer $is_delete
 * @property integer $addtime
 */
class FileGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%file_group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'is_default', 'is_delete', 'addtime'], 'integer'],
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
            'name' => '名称',
            'is_default' => '是否可编辑',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
        ];
    }
}
