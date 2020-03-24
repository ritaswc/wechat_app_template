<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%pt_robot}}".
 *
 * @property string $id
 * @property string $name
 * @property string $pic
 * @property integer $is_delete
 * @property string $addtime
 * @property string $store_id
 */
class PtRobot extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pt_robot}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'pic', 'store_id'], 'required'],
            [['is_delete', 'addtime', 'store_id'], 'integer'],
            [['name', 'pic'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '机器人名',
            'pic' => '头像',
            'is_delete' => '是否删除',
            'addtime' => '添加时间',
            'store_id' => 'Store ID',
        ];
    }
}
