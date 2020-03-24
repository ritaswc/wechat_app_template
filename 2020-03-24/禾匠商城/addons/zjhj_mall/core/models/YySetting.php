<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%yy_setting}}".
 *
 * @property string $store_id
 * @property integer $cat
 * @property string $success_notice
 * @property string $refund_notice
 * @property integer $is_share
 * @property integer $is_sms
 * @property integer $is_print
 * @property integer $is_mail
 */
class YySetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%yy_setting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'cat'], 'required'],
            [['store_id', 'cat', 'is_share', 'is_sms', 'is_print', 'is_mail'], 'integer'],
            [['success_notice', 'refund_notice'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'store_id' => '是否显示分类',
            'cat' => '参数',
            'success_notice' => '预约成功模板通知',
            'refund_notice' => '退款模板id',
            'is_share' => '是否开启分销 0--关闭 1--开启',
            'is_sms' => '是否开启短信通知',
            'is_print' => '是否开启订单打印',
            'is_mail' => '是否开启邮件通知',
        ];
    }
}
