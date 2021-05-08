<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;

/**
 * This is the model class for table "{{%integral_coupon_order}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $user_id
 * @property integer $coupon_id
 * @property string $order_no
 * @property integer $is_pay
 * @property integer $pay_time
 * @property string $price
 * @property integer $integral
 * @property integer $addtime
 */
class IntegralCouponOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%integral_coupon_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'user_id', 'coupon_id', 'order_no', 'is_pay', 'addtime'], 'required'],
            [['store_id', 'user_id', 'coupon_id', 'is_pay', 'pay_time', 'integral', 'addtime'], 'integer'],
            [['price'], 'number'],
            [['order_no'], 'string', 'max' => 255],
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
            'user_id' => 'User ID',
            'coupon_id' => 'Coupon ID',
            'order_no' => 'Order No',
            'is_pay' => 'Is Pay',
            'pay_time' => 'Pay Time',
            'price' => 'Price',
            'integral' => 'Integral',
            'addtime' => 'Addtime',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        CommonActionLog::storeActionLog('', $insert, 0, $data, $this->id);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(),['id' => 'user_id']);
    }
}
