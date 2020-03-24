<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%bargain_setting}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $is_print
 * @property integer $is_share
 * @property integer $is_sms
 * @property integer $is_mail
 * @property string $content
 * @property string $share_title
 */
class BargainSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bargain_setting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'is_print', 'is_share', 'is_sms', 'is_mail'], 'integer'],
            [['share_title'], 'string'],
            [['content'], 'string', 'max' => 255],
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
            'is_print' => '是否打印 0--否 1--是',
            'is_share' => '是否参与分销 0--不参与 1--参与',
            'is_sms' => '是否发送短信 0--否 1--是',
            'is_mail' => '是否发送邮件 0--否 1--是',
            'content' => '活动规则',
            'share_title' => 'Share Title',
        ];
    }
}
