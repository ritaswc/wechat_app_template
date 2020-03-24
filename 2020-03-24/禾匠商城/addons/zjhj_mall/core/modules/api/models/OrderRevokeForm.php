<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/20
 * Time: 10:25
 */

namespace app\modules\api\models;

use Alipay\AlipayRequestFactory;
use app\models\OrderUnion;
use app\utils\Refund;
use app\utils\SendMail;
use app\utils\Sms;
use app\models\Goods;
use app\models\Order;
use app\models\OrderDetail;
use app\models\Register;
use app\models\User;
use app\models\UserAccountLog;
use app\models\UserCoupon;
use app\models\WechatTplMsgSender;
use app\models\StepUser;
use app\models\StepLog;

class OrderRevokeForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $order_id;
    public $delete_pass = false;

    public function rules()
    {
        return [
            [['order_id'], 'required'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $order = Order::findOne([
            'store_id' => $this->store_id,
            'user_id' => $this->user_id,
            'id' => $this->order_id,
            'is_send' => 0,
            'is_delete' => 0,
            'is_cancel' => 0
        ]);
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在'
            ];
        }
        //已支付订单需要后台先审核
        if ($order->is_pay == 1 && !$this->delete_pass) {
            $order->apply_delete = 1;
            Sms::send_refund($order->store_id, $order->order_no);
            $mail = new SendMail($order->store_id, $order->id, 0);
            $mail->send_refund();
            if ($order->save()) {
                return [
                    'code' => 0,
                    'msg' => '订单取消申请已提交，请等候管理员审核'
                ];
            } else {
                return $this->getErrorResponse($order);
            }
        }

        $order->is_delete = 1;
        $order_detail_list = OrderDetail::find()->where(['order_id' => $order->id, 'is_delete' => 0])->all();

        $t = \Yii::$app->db->beginTransaction();

        //库存恢复
        foreach ($order_detail_list as $order_detail) {
            $goods = Goods::findOne($order_detail->goods_id);
            $attr_id_list = [];
            foreach (json_decode($order_detail->attr) as $item) {
                array_push($attr_id_list, $item->attr_id);
            }
            $goods->numAdd($attr_id_list, $order_detail->num);
            /*
            if (!$goods->numAdd($attr_id_list, $order_detail->num)) {
                $t->rollBack();
                return [
                    'code' => 1,
                    'msg' => '订单取消失败，库存操作失败',
                ];
            }
            */
        }
        // 活力币恢复
        if($order->type == 5 && $order->currency > 0){
            $step_user = StepUser::findOne(['store_id' => $this->store_id, 'user_id' => $order->user_id, 'is_delete' => 0]);
            $step_user->step_currency = floatval($order->currency + $step_user->step_currency);
            $step_user->save();

            $step_log = new StepLog();
            $step_log->store_id = $this->store_id;
            $step_log->step_id = $step_user->id;
            $step_log->num = 0;
            $step_log->status = 1;
            $step_log->type = 1;
            $step_log->step_currency = $order->currency;
            $step_log->type_id = $order->id;
            $step_log->raffle_time = time();
            $step_log->create_time = time();
            $step_log->save();
        }

        // 用户积分恢复
        $integral = json_decode($order->integral)->forehead_integral;
        $user = User::findOne(['id' => $order->user_id]);
        if ($integral > 0) {
            $user->integral += $integral;
        }
        //余额支付 退换余额
        if ($order->is_pay == 1 && $order->pay_type == 3) {
            $user->money += floatval($order->pay_price);
            $log = new UserAccountLog();
            $log->user_id = $user->id;
            $log->type = 1;
            $log->price = $order->pay_price;
            $log->desc = "商城订单退款,订单号（{$order->order_no}）";
            $log->addtime = time();
            $log->order_id = $order->id;
            $log->order_type = 4;
            $log->save();
        }
        if (!$user->save()) {
            $register = new Register();
            $register->store_id = $this->store_id;
            $register->user_id = $user->id;
            $register->register_time = '..';
            $register->addtime = time();
            $register->continuation = 0;
            $register->type = 6;
            $register->integral = $integral;
            $register->order_id = $order->id;
            $register->save();
            $t->rollBack();
            return [
                'code' => 1,
                'msg' => $this->getErrorResponse($user),
            ];
        }

        //已付款就退款
        if ($order->is_pay == 1 && $order->pay_type == 1) {
            if ($order->pay_price > 0) {
                $refund_res = Refund::refund($order, $order->order_no, $order->pay_price);
                if ($refund_res !== true) {
                    $t->rollBack();
                    return $refund_res;
                }
            }
        }

        if ($order->save()) {
            if ($order->is_pay == 0) {
                UserCoupon::updateAll(['is_use' => 0], ['id' => $order->user_coupon_id]);
            }

            $t->commit();

            return [
                'code' => 0,
                'msg' => '订单已取消'
            ];
        } else {
            $t->rollBack();
            return [
                'code' => 1,
                'msg' => '订单取消失败'
            ];
        }
    }

    /**
     * 微信支付退款
     * @param $order
     * @param null $refund_account
     * @return array|bool
     */
    private function wxRefund($order, $refund_account = null)
    {
        $wechat = $this->getWechat();
        $data = [
            'out_trade_no' => $order->order_no,
            'out_refund_no' => $order->order_no,
            'total_fee' => $order->pay_price * 100,
            'refund_fee' => $order->pay_price * 100,
        ];

        if (isset($order->order_union_id) && $order->order_union_id != 0) {
            // 多商户合并订单退款
            $orderUnion = OrderUnion::findOne($order->order_union_id);
            if (!$orderUnion) {
                return [
                    'code' => 1,
                    'msg' => '订单取消失败，合并支付订单不存在。',
                ];
            }
            $data['out_trade_no'] = $orderUnion->order_no;
            $data['total_fee'] = $orderUnion->price * 100;
        }

        if ($refund_account) {
            $data['refund_account'] = $refund_account;
        }
        $res = $wechat->pay->refund($data);
        if (!$res) {
            return [
                'code' => 1,
                'msg' => '订单取消失败，退款失败，服务端配置出错',
            ];
        }
        if ($res['return_code'] != 'SUCCESS') {
            return [
                'code' => 1,
                'msg' => '订单取消失败，退款失败，' . $res['return_msg'],
                'res' => $res,
            ];
        }
        if (isset($res['err_code']) && $res['err_code'] == 'NOTENOUGH' && !$refund_account) {
            // 交易未结算资金不足，请使用可用余额退款
            return $this->wxRefund($order, 'REFUND_SOURCE_RECHARGE_FUNDS');
        }
        if ($res['result_code'] != 'SUCCESS') {
            return [
                'code' => 1,
                'msg' => '订单取消失败，退款失败，' . $res['err_code_des'],
                'res' => $res,
            ];
        }
        return true;
    }
}
