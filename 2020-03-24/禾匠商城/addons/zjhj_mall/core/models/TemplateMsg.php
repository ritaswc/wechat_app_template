<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%template_msg}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $tpl_name
 * @property string $tpl_id
 */
class TemplateMsg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%template_msg}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'tpl_name', 'tpl_id'], 'required'],
            [['store_id'], 'integer'],
            [['tpl_name'], 'string', 'max' => 32],
            [['tpl_id'], 'string', 'max' => 64],
            [['store_id', 'tpl_name'], 'unique', 'targetAttribute' => ['store_id', 'tpl_name'], 'message' => 'The combination of Store ID and Tpl Name has already been taken.'],
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
            'tpl_name' => 'Tpl Name',
            'tpl_id' => 'Tpl ID',
        ];
    }

}
