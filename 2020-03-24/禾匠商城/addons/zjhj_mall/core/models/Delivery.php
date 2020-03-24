<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%delivery}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $express_id
 * @property string $customer_name
 * @property string $customer_pwd
 * @property string $month_code
 * @property string $send_site
 * @property string $send_name
 * @property integer $is_delete
 * @property integer $addtime
 * @property string $template_size
 */
class Delivery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%delivery}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'express_id', 'is_delete', 'addtime'], 'integer'],
            [['customer_name', 'customer_pwd', 'month_code', 'send_site', 'send_name', 'template_size'], 'string', 'max' => 255],
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
            'express_id' => '快递公司id',
            'customer_name' => '电子面单客户账号',
            'customer_pwd' => '电子面单密码',
            'month_code' => '月结编码',
            'send_site' => '网点编码',
            'send_name' => '网点名称',
            'is_delete' => 'Is Delete',
            'addtime' => 'Addtime',
            'template_size' => '快递鸟电子面单模板规格',
        ];
    }
}
