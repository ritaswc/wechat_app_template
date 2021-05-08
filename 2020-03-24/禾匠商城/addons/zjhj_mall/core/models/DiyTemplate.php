<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%diy_template}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $name
 * @property string $template
 * @property integer $is_delete
 * @property integer $addtime
 */
class DiyTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%diy_template}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'template', 'addtime'], 'required'],
            [['store_id', 'is_delete', 'addtime'], 'integer'],
            [['template'], 'string'],
            [['name'], 'string', 'max' => 45],
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
            'name' => 'Name',
            'template' => 'Template',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
        ];
    }
}
