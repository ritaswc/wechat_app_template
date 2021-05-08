<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%territorial_limitation}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $detail
 * @property integer $addtime
 * @property integer $is_enable
 * @property integer $is_delete
 */
class TerritorialLimitation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%territorial_limitation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'detail'], 'required'],
            [['store_id', 'addtime', 'is_enable', 'is_delete'], 'integer'],
            [['detail'], 'string'],
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
            'detail' => 'Detail',
            'addtime' => 'Addtime',
            'is_enable' => 'Is Enable',
            'is_delete' => 'Is Delete',
        ];
    }
}
