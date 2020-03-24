<?php

namespace app\models;

use Yii;
use Codeception\PHPUnit\ResultPrinter\HTML;

/**
 * This is the model class for table "{{%form}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $name
 * @property string $type
 * @property integer $required
 * @property string $default
 * @property string $tip
 * @property integer $sort
 * @property integer $is_delete
 * @property integer $addtime
 */
class Form extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%form}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'required', 'sort', 'is_delete', 'addtime'], 'integer'],
            [['name', 'type', 'default', 'tip'], 'string', 'max' => 255],
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
            'type' => '类型',
            'required' => '必填项',
            'default' => '默认值',
            'tip' => '提示语',
            'sort' => '排序',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
        ];
    }

    public function beforeSave($insert)
    {
        $this->name = \yii\helpers\Html::encode($this->name);
        $this->tip = \yii\helpers\Html::encode($this->tip);
        $this->default = \yii\helpers\Html::encode($this->default);
        return parent::beforeSave($insert);
    }
}
