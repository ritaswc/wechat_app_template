<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%auth_role_permission}}".
 *
 * @property integer $id
 * @property integer $role_id
 * @property string $permission_name
 */
class AuthRolePermission extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_role_permission}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'permission_name'], 'required'],
            [['role_id'], 'integer'],
            [['permission_name'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_id' => 'Role ID',
            'permission_name' => 'Permission Name',
        ];
    }
}
