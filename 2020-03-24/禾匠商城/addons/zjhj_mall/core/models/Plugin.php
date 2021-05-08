<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%plugin}}".
 *
 * @property integer $id
 * @property string $data
 * @property string $name
 * @property string $display_name
 * @property string $route
 * @property integer $addtime
 */
class Plugin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%plugin}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data'], 'required'],
            [['data'], 'string'],
            [['addtime'], 'integer'],
            [['name', 'display_name', 'route'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'data' => 'Data',
            'name' => 'Name',
            'display_name' => 'Display Name',
            'route' => 'Route',
            'addtime' => 'Addtime',
        ];
    }
}
