<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%favorite}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property integer $goods_id
 * @property integer $addtime
 * @property integer $is_delete
 */
class Favorite extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%favorite}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id', 'goods_id', 'addtime'], 'required'],
            [['store_id', 'user_id', 'goods_id', 'addtime', 'is_delete'], 'integer'],
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
            'goods_id' => 'Goods ID',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
        ];
    }
}
