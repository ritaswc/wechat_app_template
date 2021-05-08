<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%step_setting}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $rule
 * @property string $title
 * @property integer $convert_max
 * @property integer $convert_ratio
 * @property integer $invite_ratio
 * @property integer $activity_rule
 * @property string $share_title
 * @property string $qrcode_title
 * @property integer $ranking_num
 */
class StepSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%step_setting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'convert_max', 'convert_ratio', 'invite_ratio', 'ranking_num'], 'integer'],
            [['rule', 'activity_rule', 'share_title'], 'string', 'max' => 2000],
            [['title', 'qrcode_title'], 'string', 'max' => 255],
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
            'rule' => '规则',
            'title' => '小程序标题',
            'convert_max' => '最大限制',
            'convert_ratio' => '兑换比率',
            'invite_ratio' => '邀请比率',
            'activity_rule' => '活动规则',
            'share_title' => '转发标题',
            'ranking_num' => '全国排行限制',
            'qrcode_title' => '海报文字',
        ];
    }
}
