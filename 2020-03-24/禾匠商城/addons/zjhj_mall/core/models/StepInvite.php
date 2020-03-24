<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%step_invite}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $child_id
 * @property integer $ratio
 * @property integer $create_time
 */
class StepInvite extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%step_invite}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'child_id', 'ratio', 'create_time'], 'required'],
            [['store_id', 'child_id', 'ratio', 'create_time','step_id'], 'integer'],
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
            'child_id' => 'Child ID',
            'ratio' => 'Invite',
            'create_time' => 'Create Time',
            'step_id' => '12',
        ];
    }

    public function getChild()
    {
        return $this->hasOne(StepUser::className(), ['id' => 'child_id']);
    }

    public function getParent()
    {
        return $this->hasOne(StepUser::className(), ['id' => 'parent_id']);
    }
}
