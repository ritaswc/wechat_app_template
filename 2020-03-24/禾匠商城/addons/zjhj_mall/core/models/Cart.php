<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%cart}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property integer $goods_id
 * @property integer $num
 * @property integer $addtime
 * @property integer $is_delete
 * @property string $attr
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cart}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id', 'goods_id', 'attr'], 'required'],
            [['store_id', 'user_id', 'goods_id', 'num', 'addtime', 'is_delete'], 'integer'],
            [['attr'], 'string'],
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
            'user_id' => '用户id',
            'goods_id' => '商品id',
            'num' => '商品数量',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
            'attr' => '规格',
        ];
    }
}
