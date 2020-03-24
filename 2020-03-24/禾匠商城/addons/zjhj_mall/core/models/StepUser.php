<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%step_user}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $parent_id
 * @property string $step_currency
 * @property integer $user_id
 * @property integer $ratio
 * @property integer $create_tiem
 * @property integer $remind
 */
class StepUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%step_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id', 'ratio', 'create_time', 'parent_id', 'remind'], 'integer'],
            [['step_currency'], 'number'],
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
            'step_currency' => 'Step Currency',
            'user_id' => '用户ID',
            'ratio' => '概率加成',
            'create_time' => '创建时间',
            'remind' => '模板消息',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getParent()
    {
        return $this->hasOne(User::className(), ['id' => 'parent_id']);
    }

    public function getChild()
    {
        return $this->hasMany(StepInvite::className(), ['child_id' => 'id']);
    }

    public function getInvite()
    {
        return $this->hasOne(StepInvite::className(), ['parent_id' => 'id']); 
    }
}
