<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hjmall_gwd_setting".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $status
 */
class GwdSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gwd_setting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id'], 'required'],
            [['store_id', 'status'], 'integer'],
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
            'status' => 'Status',
        ];
    }
}
