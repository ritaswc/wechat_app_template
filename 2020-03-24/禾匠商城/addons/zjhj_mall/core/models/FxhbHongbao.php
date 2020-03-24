<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%fxhb_hongbao}}".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $store_id
 * @property integer $user_id
 * @property integer $user_num
 * @property string $coupon_total_money
 * @property string $coupon_money
 * @property string $coupon_use_minimum
 * @property integer $coupon_expire
 * @property integer $distribute_type
 * @property integer $is_expire
 * @property integer $expire_time
 * @property integer $is_finish
 * @property integer $addtime
 * @property string $form_id
 */
class FxhbHongbao extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fxhb_hongbao}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'store_id', 'user_id', 'user_num', 'coupon_expire', 'distribute_type', 'is_expire', 'expire_time', 'is_finish', 'addtime'], 'integer'],
            [['store_id', 'user_id', 'user_num', 'coupon_total_money', 'coupon_money', 'coupon_use_minimum', 'distribute_type', 'expire_time'], 'required'],
            [['coupon_total_money', 'coupon_money', 'coupon_use_minimum'], 'number'],
            [['form_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'store_id' => 'Store ID',
            'user_id' => 'User ID',
            'user_num' => '拆红包所需用户数,最少2人',
            'coupon_total_money' => '红包总金额',
            'coupon_money' => '分到的红包金额',
            'coupon_use_minimum' => '红包使用最低消费金额',
            'coupon_expire' => '优惠券有效期，天',
            'distribute_type' => '红包分配类型：0=随机，1=平分',
            'is_expire' => '是否已过期',
            'expire_time' => '到期时间',
            'is_finish' => '是否已完成',
            'addtime' => 'Addtime',
            'form_id' => '小程序form_id',
        ];
    }
}
