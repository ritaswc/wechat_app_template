<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%fxhb_setting}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_num
 * @property string $coupon_total_money
 * @property string $coupon_use_minimum
 * @property integer $coupon_expire
 * @property integer $distribute_type
 * @property string $tpl_msg_id
 * @property integer $game_time
 * @property integer $game_open
 * @property string $rule
 * @property string $share_pic
 * @property string $share_title
 */
class FxhbSetting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fxhb_setting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id'], 'required'],
            [['store_id', 'user_num', 'coupon_expire', 'distribute_type', 'game_time', 'game_open'], 'integer'],
            [['coupon_total_money', 'coupon_use_minimum'], 'number'],
            [['rule', 'share_pic', 'share_title'], 'string'],
            [['tpl_msg_id'], 'string', 'max' => 255],
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
            'user_num' => '拆红包所需用户数,最少2人',
            'coupon_total_money' => '红包总金额',
            'coupon_use_minimum' => '赠送的优惠券最低消费金额',
            'coupon_expire' => '红包优惠券有效期',
            'distribute_type' => '红包分配类型：0=随机，1=平分',
            'tpl_msg_id' => '红包到账通知模板消息id',
            'game_time' => '每个红包有效期,单位：小时',
            'game_open' => '是否开启活动，0=不开启，1=开启',
            'rule' => '规则',
            'share_pic' => 'Share Pic',
            'share_title' => 'Share Title',
        ];
    }
}
