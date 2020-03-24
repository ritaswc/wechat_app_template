<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2018/5/21
 * Time: 11:48
 */

namespace app\modules\api\models\integralmall;

use app\utils\PayNotify;
use app\modules\api\models\ApiModel;
use app\models\Coupon;
use app\models\User;
use app\models\UserCoupon;
use app\models\IntegralCouponOrder;
use app\models\FormId;
use app\models\Register;
use Alipay\AlipayRequestFactory;
class CouponOrderForm extends ApiModel
{
    public $store_id;
    public $user;
    public $id;
    public $type;
    public $wechat;
    public $order;

    public function exchangeCoupon()
    {
        $coupon = Coupon::find()->where(['id' => $this->id,'is_delete'=>0, 'store_id' => $this->store_id, 'is_integral' => 2])->one();
        $user = User::findOne(['id' => $this->user->id, 'store_id' => $this->store_id]);
        if (!$coupon) {
            return [
                'code'=>1,
                'msg'=>'网络异常'
            ];
        }
        if (!$user) {
            return [
                'code'=>1,
                'msg'=>'网络异常'
            ];
        }
        if ($coupon->total_num <= 0) {
            return [
                'code' => 1,
                'msg' => '优惠券已领完'
            ];
        }
        $count = IntegralCouponOrder::find()->where(['user_id'=>$user->id,'is_pay'=>1,'store_id'=>$this->store_id,'coupon_id' => $this->id])->count();
        if ($count >= $coupon->user_num) {
            return [
                'code' => 1,
                'msg' => '兑换次数已达上限'
            ];
        }
        if ($user->integral < $coupon->integral) {
            return [
                'code' => 1,
                'msg' => '积分不足'
            ];
        }
        $icOrder = new IntegralCouponOrder();
        $icOrder->store_id = $this->store_id;
        $icOrder->user_id = $this->user->id;
        $icOrder->coupon_id = $this->id;
        $icOrder->order_no = $this->getOrderNo();
        if ($this->type == 1) {
            $icOrder->is_pay = 1;
            $icOrder->price = 0;
            $icOrder->pay_time = time();
        } else {
            $icOrder->is_pay = 0;
            $icOrder->price = $coupon->price;
        }
        $icOrder->addtime = time();
        $icOrder->integral = $coupon->integral;
        if ($icOrder->save()) {
            if ($this->type == 1) {
                $coupon->total_num -= 1;
                if ($coupon->save()) {
                    $new_coupon = new Coupon();
                    $new_coupon->attributes = $coupon->attributes;
                    $new_coupon->is_delete = 1;
                    if ($new_coupon->save()) {
                        $userCoupon = new UserCoupon();
                        $userCoupon->store_id = $this->store_id;
                        $userCoupon->user_id = $this->user->id;
                        $userCoupon->coupon_id = $new_coupon->id;
                        $userCoupon->coupon_auto_send_id = 0;
                        $userCoupon->is_expire = 0;
                        $userCoupon->is_use = 0;
                        $userCoupon->is_delete = 0;
                        $userCoupon->addtime = time();
                        $userCoupon->type = 3;
                        $userCoupon->price = 0;
                        $userCoupon->integral = $coupon->integral;
                        if ($coupon->expire_type == 1) {
                            $userCoupon->begin_time = time();
                            $userCoupon->end_time = time() + max(0, 86400 * $coupon->expire_day);
                        } elseif ($coupon->expire_type == 2) {
                            $userCoupon->begin_time = $coupon->begin_time;
                            $userCoupon->end_time = $coupon->end_time;
                        }
                        if ($userCoupon->save()) {
                            $user->integral -= $coupon->integral;
                            $register = new Register();
                            $register->store_id = $this->store_id;
                            $register->user_id = $user->id;
                            $register->register_time = '..';
                            $register->addtime = time();
                            $register->continuation = 0;
                            $register->type = 10;
                            $register->integral = '-'.$coupon->integral;
                            $register->order_id = $userCoupon->id;
                            $register->save();
                            if ($user->save()) {
                                return [
                                    'code' => 0,
                                    'msg' => '恭喜您，兑换成功！'
                                ];
                            }
                        }
                    } else {
                        return $this->getErrorResponse($new_coupon);
                    }
                } else {
                    return $this->getErrorResponse($coupon);
                }
            } else {
                $this->wechat = $this->getWechat();
                $this->order = $icOrder;
                $body = "充值";

                if (\Yii::$app->fromAlipayApp()) {
                    $request = AlipayRequestFactory::create('alipay.trade.create', [
                        'notify_url' => pay_notify_url('/in-alipay-notify.php'),
                        'biz_content' => [
                            'body' => $body, // 对一笔交易的具体描述信息。如果是多种商品，请将商品描述字符串累加
                            'subject' => $body, // 商品的标题 / 交易标题 / 订单标题 / 订单关键字等
                            'out_trade_no' => $this->order->order_no, // 商户网站唯一订单号
                            'total_amount' => $this->order->pay_price, // 订单总金额，单位为元，精确到小数点后两位，取值范围 [0.01,100000000]
                            'buyer_id' => $this->user->wechat_open_id, // 购买人的支付宝用户 ID
                            
                        ],
                    ]);
    
                    $aop = $this->getAlipay();
                    $res = $aop->execute($request)->getData();
    
                    return [
                        'code' => 0,
                        'msg' => '恭喜您，兑换成功！',
                        'data' => $res,
                        'res' => $res,
                    ];
                }

                $res = $this->unifiedOrder($body);
                if (isset($res['code']) && $res['code'] == 1) {
                    return $res;
                }
                //记录prepay_id发送模板消息用到
                FormId::addFormId([
                    'store_id' => $this->store_id,
                    'user_id' => $this->user->id,
                    'wechat_open_id' => $this->user->wechat_open_id,
                    'form_id' => $res['prepay_id'],
                    'type' => 'prepay_id',
                    'order_no' => $this->order->order_no,
                ]);

                $pay_data = [
                    'appId' => $this->wechat->appId,
                    'timeStamp' => '' . time(),
                    'nonceStr' => md5(uniqid()),
                    'package' => 'prepay_id=' . $res['prepay_id'],
                    'signType' => 'MD5',
                ];
                $pay_data['paySign'] = $this->wechat->pay->makeSign($pay_data);
                return [
                    'code' => 0,
                    'msg' => '恭喜您，兑换成功！',
                    'data' => (object)$pay_data,
                    'res' => $res,
                ];
            }
        } else {
            return $this->getErrorResponse($icOrder);
        }
    }

    public function getOrderNo()
    {
        $store_id = empty($this->store_id) ? 0 : $this->store_id;
        $order_no = null;
        while (true) {
            $order_no = 'I' . date('YmdHis') . mt_rand(100000, 999999);
            $exist_order_no = IntegralCouponOrder::find()->where(['order_no' => $order_no])->exists();
            if (!$exist_order_no) {
                break;
            }
        }
        return $order_no;
    }


    private function unifiedOrder($body)
    {
        $res = $this->wechat->pay->unifiedOrder([
            'body' => $body,
            'out_trade_no' => $this->order->order_no,
            'total_fee' => $this->order->price * 100,
            'notify_url' => pay_notify_url('/in-pay-notify.php'),
            'trade_type' => 'JSAPI',
            'openid' => $this->user->wechat_open_id,
        ]);
        if (!$res) {
            return [
                'code' => 1,
                'msg' => '支付失败',
            ];
        }
        if ($res['return_code'] != 'SUCCESS') {
            return [
                'code' => 1,
                'msg' => '支付失败，' . (isset($res['return_msg']) ? $res['return_msg'] : ''),
                'res' => $res,
            ];
        }
        if ($res['result_code'] != 'SUCCESS') {
            if ($res['err_code'] == 'INVALID_REQUEST') {//商户订单号重复
                $this->order->order_no = $this->getOrderNo();
                $this->order->save();
                return $this->unifiedOrder($body);
            } else {
                return [
                    'code' => 1,
                    'msg' => '支付失败，' . (isset($res['err_code_des']) ? $res['err_code_des'] : ''),
                    'res' => $res,
                ];
            }
        }
        return $res;
    }
}
