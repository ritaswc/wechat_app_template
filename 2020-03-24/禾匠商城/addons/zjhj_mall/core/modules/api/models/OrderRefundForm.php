<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/8
 * Time: 11:56
 */

namespace app\modules\api\models;

use app\models\MsOrder;
use app\models\MsOrderRefund;
use app\models\PtOrder;
use app\models\PtOrderDetail;
use app\models\PtOrderRefund;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderRefund;
use app\models\OrderWarn;
use yii\helpers\Html;

class OrderRefundForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $order_detail_id;
    public $type;
    public $desc;
    public $pic_list;
    public $orderType;
    public $form_id;

    const MS = 'MIAOSHA';
    const PT = 'PINTUAN';

    public function rules()
    {
        return [
            [['desc', 'orderType', 'form_id'], 'trim'],
            [['type', 'desc', 'order_detail_id'], 'required'],
            [['type',], 'in', 'range' => [1, 2]],
            [['pic_list',], 'string'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        if ($this->orderType === self::MS) {
            $query = MsOrder::find();
            $queryOrder = MsOrder::find();
            $queryOrderRefund = MsOrderRefund::find();
            $refund = new MsOrderRefund();

        } elseif ($this->orderType === self::PT) {
            $query = PtOrderDetail::find();
            $queryOrder = PtOrder::find();
            $queryOrderRefund = PtOrderRefund::find();
            $refund = new PtOrderRefund();

        } else {
            $query = OrderDetail::find();
            $queryOrder = Order::find();
            $queryOrderRefund = OrderRefund::find();
            $refund = new OrderRefund();

        }

        $order_detail = $query->where([
            'id' => $this->order_detail_id,
            'is_delete' => 0,
        ])->one();

        if (!$order_detail) {
            return [
                'code' => 1,
                'msg' => '订单商品不存在，无法申请售后',
            ];
        }

        $order = $queryOrder->where([
            // 秒杀订单没有详情
            'id' => isset($order_detail->order_id) ? $order_detail->order_id : $order_detail->id,
            'is_delete' => 0,
            'user_id' => $this->user_id,
        ])->one();

        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在，无法申请售后',
            ];
        }
        if ($order->is_pay != 1) {
            return [
                'code' => 1,
                'msg' => '订单尚未支付，无法申请售后',
            ];
        }

        $queryOrderRefund->where([
            'store_id' => $this->store_id,
            'user_id' => $this->user_id,
            'is_delete' => 0,
        ]);

        if ($this->orderType !== self::MS) {
            $existRefund = $queryOrderRefund->andWhere(['order_detail_id' => $this->order_detail_id])->one();
        } else {
            $existRefund = $queryOrderRefund->andWhere(['order_id' => $order->id])->one();
        }

        if ($existRefund) {
            return [
                'code' => 1,
                'msg' => '该商品已申请过售后，请不要重复申请',
            ];
        }

        $refund->store_id = $this->store_id;
        $refund->user_id = $this->user_id;
        $refund->order_id = $order->id;

        if ($this->orderType !== self::MS) {
            $refund->order_detail_id = $order_detail->id;

        }
        $refund->type = $this->type;
        $refund->order_refund_no = $this->getOrderRefundNo();

        if ($refund->type == 1) {
            $refund->refund_price = min($order->pay_price, $order_detail->total_price);
        } elseif ($refund->type == 2) {
            $refund->refund_price = 0;
        } else {
            return [
                'code' => 1,
                'msg' => '未知售后类型'
            ];
        }

        $refund->desc = $this->desc;
        $this->pic_list = json_decode($this->pic_list);
        $pic_list = [];
        if (is_array($this->pic_list)) {
            foreach ($this->pic_list as $item) {
                if (is_string($item)) {
                    $pic_list[] = Html::encode(trim($item));
                }
            }
        }
        $refund->pic_list = json_encode($pic_list, JSON_UNESCAPED_UNICODE);
        $refund->status = 0;
        $refund->addtime = time();

        if ($refund->save()) {
            //售后订单申请成功之后，相关操作
            $form = new OrderWarn();
            $form->store_id = $order->store_id;
            $form->order_id = $order->id;
            $form->order_refund_no = $refund->order_refund_no;
            $form->order_type = 0;
            $form->form_id = $this->form_id;
            $form->refund();
            return [
                'code' => 0,
                'msg' => '售后订单提交成功',
            ];
        } else {
            return $this->getErrorResponse($refund);
        }
    }

    private function getOrderRefundNo()
    {
        $order_refund_no = null;
        while (true) {
            $order_refund_no = date('YmdHis') . mt_rand(100000, 999999);
            $exist_order_refund_no = OrderRefund::find()->where(['order_refund_no' => $order_refund_no])->exists();
            if (!$exist_order_refund_no) {
                break;
            }
        }
        return $order_refund_no;
    }
}
