<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%color}}".
 *
 * @property integer $id
 * @property string $rgb
 * @property string $color
 * @property integer $is_delete
 * @property integer $addtime
 */
class Color extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%color}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rgb', 'color'], 'required'],
            [['is_delete', 'addtime'], 'integer'],
            [['rgb', 'color'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rgb' => 'rgb颜色码 例如：\"0，0，0\"',
            'color' => '16进制颜色码例如：#ffffff',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
        ];
    }
}
