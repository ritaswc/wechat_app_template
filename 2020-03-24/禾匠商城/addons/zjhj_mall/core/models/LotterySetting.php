<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%lottery_setting}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $rule
 * @property string $title
 * @property string $type
 */
class LotterySetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%lottery_setting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'type'], 'integer'],
            [['rule'], 'string', 'max' => 2000],
            [['title'], 'string', 'max' => 255],
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
            'rule' => '规则',
            'title' => '小程序标题',
            'type' => '是否强制'
        ];
    }
}
