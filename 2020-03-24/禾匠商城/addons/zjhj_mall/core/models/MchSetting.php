<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%mch_setting}}".
 *
 * @property integer $id
 * @property integer $mch_id
 * @property integer $store_id
 * @property integer $is_share
 */
class MchSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mch_setting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mch_id', 'store_id', 'is_share'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mch_id' => 'Mch ID',
            'store_id' => 'Store ID',
            'is_share' => '是否开启分销 0--不开启 1--开启',
        ];
    }
}
