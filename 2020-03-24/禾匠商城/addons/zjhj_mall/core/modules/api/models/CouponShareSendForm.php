<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/24
 * Time: 18:46
 */

namespace app\modules\api\models;

use app\models\Coupon;
use app\models\CouponAutoSend;
use app\models\UserCoupon;

class CouponShareSendForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $id;

    public function save()
    {
        $coupon_auto_send_list = CouponAutoSend::find()->where([
            'store_id' => $this->store_id,
            'is_delete' => 0,
            'event' => 1,
        ])->all();
        $count = 0;
        $coupon_list = [];

        foreach ($coupon_auto_send_list as $coupon_auto_send) {
            if (Coupon::userAddCoupon($this->user_id, $coupon_auto_send->coupon_id, $coupon_auto_send->id)) {
                $count++;
                $coupon = Coupon::find()->select('name,discount_type,min_price,sub_price,discount,expire_type,expire_day,begin_time,end_time,goods_id_list,appoint_type')->where(['id' => $coupon_auto_send->coupon_id])->asArray()->one();
                if ($coupon['expire_type'] == 1) {
                    $coupon['desc'] = "本券有效期为发放后{$coupon['expire_day']}天内";
                } else {
                    if($coupon['end_time'] <= time()) {
                        continue;
                    }
                    $coupon['desc'] = "本券有效期" . date('Y-m-d H:i:s', $coupon['begin_time']) . "至" . date('Y-m-d H:i:s', $coupon['end_time']);
                }
                if (count($coupon['goods_id_list']) > 0 && $coupon['appoint_type'] == 2) {
                    $goodIds = implode(",", json_decode($coupon['goods_id_list']));
                    $coupon['url'] = '/pages/list/list?goods_id=' . $goodIds;
                } else {
                    $coupon['url'] = '/pages/list/list';
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

    public function send()
    {
        $coupon = Coupon::find()->where(['store_id'=>$this->store_id,'is_delete'=>0,'id'=>$this->id])->asArray()->one();
        if (!$coupon) {
            return [
                'code'=>1,
                'msg'=>'网络异常'
            ];
        }
        $coupon['type'] = 2;
        $user_coupon = UserCoupon::find()
            ->where(['store_id'=>$this->store_id,'coupon_id'=>$this->id,'user_id'=>$this->user_id,'type'=>2,'is_delete'=>0])->exists();
        if ($user_coupon) {
            $coupon_list[] = $coupon;
            $form = new CouponListForm();
            $form->store_id = $this->store_id;
            $form->user_id = \Yii::$app->user->identity->id;
            $coupon_list_new = $form->getList();
            return [
                'code'=>1,
                'msg'=>'已领取',
                'data'=>[
                    'coupon_list'=>$coupon_list_new
                ]
            ];
        }
        $coupon_count = UserCoupon::find()->where(['store_id'=>$this->store_id,'is_delete'=>0,'coupon_id'=>$coupon['id'],'type'=>2])->count();
        if ($coupon['total_count'] != -1 && $coupon['total_count'] <= $coupon_count) {
            return [
                'code'=>1,
                'msg'=>'优惠券已领完'
            ];
        }
        $res = new UserCoupon();
        $res->user_id = $this->user_id;
        $res->store_id = $this->store_id;
        $res->coupon_id = $this->id;
        $res->coupon_auto_send_id = 0;
        $res->type = 2;
        $res->is_use = 0;
        $res->is_expire = 0;
        $res->is_delete = 0;
        $res->addtime = time();
        if ($coupon['expire_type'] == 1) {
            $res->begin_time = time();
            $res->end_time = time() + max(0, 86400 * $coupon['expire_day']);
        } elseif ($coupon['expire_type'] == 2) {
            $res->begin_time = $coupon['begin_time'];
            $res->end_time = $coupon['end_time'];
        }
        if ($res->save()) {
            $coupon_list[] = $coupon;
            $form = new CouponListForm();
            $form->store_id = $this->store_id;
            $form->user_id = \Yii::$app->user->identity->id;
            $coupon_list_new = $form->getList();
            return [
                'code'=>0,
                'msg'=>'success',
                'data' => [
                    'list' => $coupon_list,
                    'coupon_list'=>$coupon_list_new
                ],
            ];
        } else {
            return [
                'code'=>1,
                'msg'=>'网络异常'
            ];
        }
    }
}
