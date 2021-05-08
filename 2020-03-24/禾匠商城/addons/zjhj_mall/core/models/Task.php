<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%task}}".
 *
 * @property string $id
 * @property string $token
 * @property integer $delay_seconds
 * @property integer $is_executed
 * @property string $class
 * @property string $params
 * @property integer $addtime
 * @property integer $is_delete
 * @property string $content
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%task}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['token', 'delay_seconds', 'class', 'content'], 'required'],
            [['delay_seconds', 'is_executed', 'addtime', 'is_delete'], 'integer'],
            [['params', 'content'], 'string'],
            [['token'], 'string', 'max' => 128],
            [['class'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'token' => 'Token',
            'delay_seconds' => '多少秒后执行',
            'is_executed' => '是否已执行：0=未执行，1=已执行',
            'class' => 'Class',
            'params' => 'Params',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
            'content' => '任务备注',
        ];
    }

    public function getAddTimeText()
    {
        return date('Y-m-d H:i:s', $this->addtime);
    }

    public function getExecutedText()
    {
        return $this->is_executed == 0 ? '未执行' : '已执行';
    }
}
