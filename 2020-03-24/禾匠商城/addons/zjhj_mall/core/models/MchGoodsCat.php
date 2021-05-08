<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%mch_goods_cat}}".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property integer $cat_id
 */
class MchGoodsCat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mch_goods_cat}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'cat_id'], 'required'],
            [['goods_id', 'cat_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => 'Goods ID',
            'cat_id' => 'Cat ID',
        ];
    }
}
