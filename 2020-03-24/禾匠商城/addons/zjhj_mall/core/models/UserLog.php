<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%user_log}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property string $type
 * @property string $before_change
 * @property string $after_change
 * @property integer $is_delete
 * @property integer $addtime
 */
class UserLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id', 'is_delete', 'addtime'], 'integer'],
            [['type', 'before_change', 'after_change'], 'string', 'max' => 255],
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
            'type' => '改变的字段',
            'before_change' => '改变前字段的值',
            'after_change' => '改变后字段的值',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
        ];
    }

    public static function setLogList($insert,$attributes,$changedAttributes)
    {
        if($insert){

        }else{
            foreach($changedAttributes as $key => $value){
                if($attributes[$key] != $value){
                    self::setLog($attributes,$key,$value);
                }
            }
        }
    }

    public static function setLog($attributes,$field,$value)
    {
        $model = new UserLog();
        $model->store_id = $attributes['store_id'];
        $model->user_id = $attributes['id'];
        $model->type = $field;
        $model->before_change = (string)$value;
        $model->after_change = (string)$attributes[$field];
        $model->is_delete = Model::IS_DELETE_FALSE;
        $model->addtime = time();

        if (!$model->save()) {
            return [
                'code' => 1,
                'msg' => '日志添加失败！'
            ];
        }
    }
}
