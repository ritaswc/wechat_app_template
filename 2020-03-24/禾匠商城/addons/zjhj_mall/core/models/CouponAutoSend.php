<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%coupon_auto_send}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $coupon_id
 * @property integer $event
 * @property integer $send_times
 * @property integer $addtime
 * @property integer $is_delete
 */
class CouponAutoSend extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon_auto_send}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'coupon_id'], 'required'],
            [['store_id', 'coupon_id', 'event', 'send_times', 'addtime', 'is_delete'], 'integer'],
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
            'coupon_id' => 'Coupon ID',
            'event' => '触发事件：1=分享，2=购买并付款',
            'send_times' => '最多发放次数，0表示不限制',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
        ];
    }

    public function getCoupon()
    {
        return $this->hasOne(Coupon::className(), ['id' => 'coupon_id']);
    }
}
