<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%core_user}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $auth_key
 * @property string $access_token
 * @property integer $addtime
 * @property integer $is_delete
 */
class CoreUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%core_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'auth_key', 'access_token'], 'required'],
            [['addtime', 'is_delete'], 'integer'],
            [['username', 'password', 'auth_key', 'access_token'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
        ];
    }
}
