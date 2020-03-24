<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%shop_pic}}".
 *
 * @property integer $id
 * @property integer $shop_id
 * @property integer $store_id
 * @property string $pic_url
 * @property integer $is_delete
 */
class ShopPic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_pic}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id', 'store_id', 'is_delete'], 'integer'],
            [['pic_url'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_id' => 'Shop ID',
            'store_id' => 'Store ID',
            'pic_url' => 'Pic Url',
            'is_delete' => 'Is Delete',
        ];
    }
}
