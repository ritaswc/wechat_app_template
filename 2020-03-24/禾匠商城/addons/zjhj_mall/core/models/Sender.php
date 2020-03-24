<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%sender}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $company
 * @property string $name
 * @property string $tel
 * @property string $mobile
 * @property string $post_code
 * @property string $province
 * @property string $city
 * @property string $exp_area
 * @property string $address
 * @property integer $is_delete
 * @property integer $addtime
 * @property integer $delivery_id
 */
class Sender extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sender}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'is_delete', 'addtime', 'delivery_id'], 'integer'],
            [['company', 'name', 'tel', 'mobile', 'post_code', 'province', 'city', 'exp_area', 'address'], 'string', 'max' => 255],
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
            'company' => 'Company',
            'name' => 'Name',
            'tel' => 'Tel',
            'mobile' => 'Mobile',
            'post_code' => 'Post Code',
            'province' => 'Province',
            'city' => 'City',
            'exp_area' => 'Exp Area',
            'address' => 'Address',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
            'delivery_id' => 'Delivery ID',
        ];
    }
}
