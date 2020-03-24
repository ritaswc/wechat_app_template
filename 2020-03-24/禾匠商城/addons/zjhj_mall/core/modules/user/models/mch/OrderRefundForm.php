<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/3
 * Time: 15:49
 */

namespace app\modules\user\models\mch;

use app\models\Goods;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderRefund;
use app\models\User;
use app\models\UserAccountLog;
use app\models\WechatTplMsgSender;
use app\modules\user\models\UserModel;
use app\utils\Refund;
use yii\helpers\VarDumper;

class OrderRefundForm extends UserModel
{
    public $mch_id;
    public $store_id;
    public $order_refund_id;
    public $type;
    public $action;

    public function rules()
    {
        return [
            [['store_id', 'order_refund_id', 'type', 'action'], 'required'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $order_refund = OrderRefund::findOne([
            'id' => $this->order_refund_id,
            'store_id' => $this->store_id,
            'is_delete' => 0,
        ]);
        if (!$order_refund) {
            return [
                'code' => 1,
                'msg' => '售后订单不存在，请刷新页面'
            ];
        }
        if ($order_refund->status != 0) {
            return [
                'code' => 1,
                'msg' => '售后订单已经处理过了，请刷新页面',
                'id' => $order_refund->id,
            ];
        }
        if ($this->type == 1) {
            return $this->submit1($order_refund);
        }
        if ($this->type == 2) {
            return $this->submit2($order_refund);
        }
    }

    /**
     * 处理退货退款
     * @param OrderRefund $order_refund
     */
    private function submit1($order_refund)
    {
        $order = Order::findOne($order_refund->order_id);
        //$user = User::findOne($order_refund->user_id);
        if ($this->action == 1) {//同意
            $order_refund->status = 1;
            $order_refund->response_time = time();
            if ($order_refund->refund_price > 0 && $order->pay_type == 1) {
                $res = Refund::refund($order,$order_refund->order_refund_no,$order_refund->refund_price);
                if($res !== true){
                    return $res;
                }
            }
            $order_detail_list = OrderDetail::find()->where(['order_id' => $order->id, 'is_delete' => 0])->all();
            //库存恢复
            foreach ($order_detail_list as $order_detail) {
                $goods = Goods::findOne($order_detail->goods_id);
                $attr_id_list = [];
                foreach (json_decode($order_detail->attr) as $item) {
                    array_push($attr_id_list, $item->attr_id);
                }
                $goods->numAdd($attr_id_list, $order_detail->num);
            }

            // 用户积分恢复
            $integral = json_decode($order->integral)->forehead_integral;
            $user = User::findOne(['id' => $order->user_id]);
            if ($integral > 0) {
                $user->integral += $integral;
            }
            if ($order_refund->refund_price > 0 && $order->pay_type == 3) {
                $user = User::findOne(['id'=>$order->user_id]);
                $user->money += floatval($order_refund->refund_price);
                $log = new UserAccountLog();
                $log->user_id = $user->id;
                $log->price = $order_refund->refund_price;
                $log->type = 1;
                $log->desc = "商户商城售后订单退款：订单号（{$order_refund->order_refund_no}）";
                $log->addtime = time();
                $log->order_id = $order->id;
                $log->order_type = 4;
                $log->save();
            }
            if (!$user->save()) {
                return [
                    'code'=>1,
                    'msg'=>$this->getErrorResponse($user)
                ];
            }
            if ($order_refund->save()) {
                $msg_sender = new WechatTplMsgSender($this->store_id, $order->id, $this->getWechat());
                $msg_sender->refundMsg($order_refund->refund_price, $order_refund->desc, '退款已完成');
                return [
                    'code' => 0,
                    'msg' => '处理成功，已完成退款退货。',
                ];
            }
            return $this->getErrorResponse($order_refund);
        }
        if ($this->action == 2) {//拒绝
            $order_refund->status = 3;
            $order_refund->response_time = time();
            if ($order_refund->save()) {
                $msg_sender = new WechatTplMsgSender($this->store_id, $order_refund->order_id, $this->getWechat());
                $msg_sender->refundMsg('0.00', $order_refund->desc, '卖家拒绝了您的退货请求');
                return [
                    'code' => 0,
                    'msg' => '处理成功，已拒绝该退货退款订单。',
                ];
            }
            return $this->getErrorResponse($order_refund);
        }
    }

    /**
     * 处理换货
     * @param OrderRefund $order_refund
     */
    private function submit2($order_refund)
    {
        if ($this->action == 1) {//同意
            $order_refund->status = 2;
            $order_refund->response_time = time();
            if ($order_refund->save()) {
                $msg_sender = new WechatTplMsgSender($this->store_id, $order_refund->order_id, $this->getWechat());
                $msg_sender->refundMsg('0.00', $order_refund->desc, '卖家已同意换货，换货无退款金额');
                return [
                    'code' => 0,
                    'msg' => '处理成功，已同意换货。',
                ];
            }
            return $this->getErrorResponse($order_refund);
        }
        if ($this->action == 2) {//拒绝
            $order_refund->status = 3;
            $order_refund->response_time = time();
            if ($order_refund->save()) {
                $msg_sender = new WechatTplMsgSender($this->store_id, $order_refund->order_id, $this->getWechat());
                $msg_sender->refundMsg('0.00', $order_refund->desc, '卖家已拒绝您的换货请求');
                return [
                    'code' => 0,
                    'msg' => '处理成功，已拒绝换货请求。',
                ];
            }
            return $this->getErrorResponse($order_refund);
        }
    }
}
