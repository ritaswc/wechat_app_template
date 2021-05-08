<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/25
 * Time: 20:12
 */

namespace app\modules\api\models;

use app\models\Coupon;
use app\models\CouponAutoSend;

class CouponPaySendForm extends ApiModel
{
    public $store_id;
    public $user_id;

    public function save()
    {
        $coupon_auto_send_list = CouponAutoSend::find()->where([
            'store_id' => $this->store_id,
            'is_delete' => 0,
            'event' => 2,
        ])->all();
        $count = 0;
        $coupon_list = [];

        foreach ($coupon_auto_send_list as $coupon_auto_send) {
            if (Coupon::userAddCoupon($this->user_id, $coupon_auto_send->coupon_id, $coupon_auto_send->id)) {
                $count++;
                $coupon = Coupon::find()->select('name,discount_type,min_price,sub_price,discount,expire_type,expire_day,begin_time,end_time')->where(['id' => $coupon_auto_send->coupon_id])->asArray()->one();
                if ($coupon['expire_type'] == 1) {
                    $coupon['desc'] = "本券有效期为发放后{$coupon['expire_day']}天内";
                } else {
                    $coupon['desc'] = "本券有效期" . date('Y-m-d H:i:s', $coupon['begin_time']) . "至" . date('Y-m-d H:i:s', $coupon['end_time']);
                }
                $coupon_list[] = $coupon;
            }
        }
        if ($count == 0) {
            return [
                'code' => 1,
                'msg' => '没有发放优惠券',
            ];
        }
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'list' => $coupon_list,
            ],
        ];
    }
}
