<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%step_remind}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $date
 * @property integer $user_id
 */
class StepRemind extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%step_remind}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id'], 'integer'],
            [['date', 'user_id'], 'required'],
            [['date'], 'safe'],
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
            'date' => 'Date',
            'user_id' => 'User ID',
        ];
    }
}
