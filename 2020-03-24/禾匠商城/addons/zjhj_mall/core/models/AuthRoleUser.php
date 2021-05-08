<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%auth_role_user}}".
 *
 * @property integer $role_id
 * @property string $user_id
 */
class AuthRoleUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_role_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'user_id'], 'required'],
            [['role_id', 'user_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_id' => 'Role ID',
            'user_id' => 'User ID',
        ];
    }

    public function getRole()
    {
        return $this->hasOne(AuthRole::className(), ['id' => 'role_id']);
    }
}
