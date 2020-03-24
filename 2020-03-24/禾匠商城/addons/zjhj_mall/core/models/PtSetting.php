<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%pt_setting}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $is_share
 * @property integer $is_sms
 * @property integer $is_print
 * @property integer $is_mail
 * @property integer $is_area
 */
class PtSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pt_setting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'is_share', 'is_sms', 'is_print', 'is_mail','is_area'], 'integer'],
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
            'is_share' => '是否开启分销 0--关闭 1--开启',
            'is_sms' => '是否开启短信通知',
            'is_print' => '是否开启订单打印',
            'is_mail' => '是否开启邮件通知',
            'is_area' => '是否开启区域限制购买',
        ];
    }
}
