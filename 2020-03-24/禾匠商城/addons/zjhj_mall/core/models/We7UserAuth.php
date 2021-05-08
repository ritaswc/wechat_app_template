<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%we7_user_auth}}".
 *
 * @property string $id
 * @property integer $we7_user_id
 * @property string $auth
 */
class We7UserAuth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%we7_user_auth}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['we7_user_id'], 'required'],
            [['we7_user_id'], 'integer'],
            [['auth'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'we7_user_id' => 'We7 User ID',
            'auth' => 'Auth',
        ];
    }
}
