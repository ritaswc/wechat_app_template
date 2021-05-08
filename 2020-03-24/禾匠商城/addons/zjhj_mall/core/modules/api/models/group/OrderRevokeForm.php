<?php
/**
 * Created by PhpStorm.
 * User: peize
 * Date: 2018/2/5
 * Time: 15:57
 */

namespace app\modules\api\models\group;


use app\utils\Refund;
use app\utils\SendMail;
use app\utils\Sms;
use app\models\OrderDetail;
use app\models\PtGoods;
use app\models\PtNoticeSender;
use app\models\PtOrder;
use app\models\PtOrderDetail;
use app\models\UserAccountLog;
use app\modules\api\models\ApiModel;
use app\models\User;

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

    /**
     * @return array
     * 订单取消
     */
    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $order = PtOrder::findOne([
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
            $order->apply_delete = 2;
            Sms::send_refund($order->store_id, $order->order_no);
            $mail = new SendMail($order->store_id, $order->id, 2);
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

        $order->is_cancel = 1;
        $order_detail_list = PtOrderDetail::find()->where(['order_id' => $order->id, 'is_delete' => 0])->all();

        $t = \Yii::$app->db->beginTransaction();

        //库存恢复
        foreach ($order_detail_list as $order_detail) {
            $goods = PtGoods::findOne($order_detail->goods_id);
            $attr_id_list = [];
            foreach (json_decode($order_detail->attr) as $item)
                array_push($attr_id_list, $item->attr_id);
            $goods->numAdd($attr_id_list, $order_detail->num);
        }

        $user = User::findOne(['id' => $order->user_id]);
        //余额支付 退换余额
        if ($order->is_pay && $order->pay_type == 3) {
            $user->money += floatval($order->pay_price);
            $log = new UserAccountLog();
            $log->user_id = $user->id;
            $log->type = 1;
            $log->price = $order->pay_price;
            $log->desc = "拼团订单退款,订单号（{$order->order_no}）";
            $log->addtime = time();
            $log->order_id = $order->id;
            $log->order_type = 6;
            $log->save();
        }
        if (!$user->save()) {
            $t->rollBack();
            return [
                'code' => 1,
                'msg' => $this->getErrorResponse($user),
            ];
        }

        //已付款就退款
        if ($order->is_pay == 1 && $order->pay_type == 1) {
            $res = Refund::refund($order,$order->order_no,$order->pay_price);
            if($res !== true){
                return $res;
            }
        }

        if ($order->save()) {
            $t->commit();
            $msg_sender = new PtNoticeSender($this->getWechat(), $this->store_id);
            if ($order->is_pay) {
                $remark = '订单已取消，退款金额：' . $order->pay_price;
                $msg_sender->revokeMsg($remark);
            } else {
                $msg_sender->revokeMsg();
            }
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

}
