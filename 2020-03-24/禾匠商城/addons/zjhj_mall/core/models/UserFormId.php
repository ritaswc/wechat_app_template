<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%user_form_id}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $form_id
 * @property integer $times
 * @property integer $addtime
 */
class UserFormId extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_form_id}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'form_id'], 'required'],
            [['user_id', 'times', 'addtime'], 'integer'],
            [['form_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'form_id' => 'Form ID',
            'times' => '剩余使用次数',
            'addtime' => 'Addtime',
        ];
    }
}
