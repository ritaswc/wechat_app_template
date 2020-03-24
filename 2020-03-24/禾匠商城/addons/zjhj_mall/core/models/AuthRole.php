<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;

/**
 * This is the model class for table "{{%auth_role}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $creator_id
 * @property string $name
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 */
class AuthRole extends \yii\db\ActiveRecord
{
    public $checked;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_role}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'name', 'creator_id'], 'required'],
            [['store_id', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 64],
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
            'creator_id' => 'Creator ID',
            'name' => 'Name',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getUser() {
        if (!Yii::$app->admin->isGuest) {
            return $this->hasOne(Admin::className(), ['id' => 'creator_id']);
        }

        return $this->hasOne(User::className(), ['id' => 'creator_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        CommonActionLog::storeActionLog('', $insert, 0, $data, $this->id);
    }

    public function afterDelete()
    {
        CommonActionLog::storeActionLog('', 'DESTROY', 1, $this->attributes, $this->id);
    }
}
