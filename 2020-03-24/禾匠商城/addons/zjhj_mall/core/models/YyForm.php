<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%yy_form}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $goods_id
 * @property string $name
 * @property string $type
 * @property integer $required
 * @property string $default
 * @property string $tip
 * @property integer $sort
 * @property integer $is_delete
 * @property integer $addtime
 * @property string $option
 */
class YyForm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%yy_form}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'goods_id', 'required', 'sort', 'is_delete', 'addtime'], 'integer'],
            [['option'], 'string'],
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
            'goods_id' => 'Goods ID',
            'name' => '字段名称',
            'type' => '字段类型',
            'required' => '是否必填',
            'default' => '默认值',
            'tip' => '提示语',
            'sort' => 'Sort',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
            'option' => '单选、复选项 值',
        ];
    }
}
