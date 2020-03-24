<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%user_auth_login}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property string $token
 * @property integer $is_pass
 * @property integer $addtime
 */
class UserAuthLogin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_auth_login}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id', 'is_pass', 'addtime'], 'integer'],
            [['token'], 'required'],
            [['token'], 'string', 'max' => 225],
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
            'user_id' => 'User ID',
            'token' => 'Token',
            'is_pass' => '是否已确认登录：0=未扫码，1=已确认登录',
            'addtime' => 'Addtime',
        ];
    }
}
