<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/8
 * Time: 11:56
 */

namespace app\modules\api\models\group;

use app\utils\SendMail;
use app\utils\Sms;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderRefund;
use app\models\OrderMessage;
use app\models\OrderWarn;
use app\models\PtOrder;
use app\models\PtOrderDetail;
use app\models\PtOrderRefund;
use app\modules\api\models\ApiModel;
use yii\helpers\Html;

class OrderRefundForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $order_detail_id;
    public $type;
    public $desc;
    public $pic_list;

    public function rules()
    {
        return [
            [['desc'], 'trim'],
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
        $order_detail = PtOrderDetail::findOne([
            'id' => $this->order_detail_id,
            'is_delete' => 0,
        ]);
        if (!$order_detail) {
            return [
                'code' => 1,
                'msg' => '订单商品不存在，无法申请售后',
            ];
        }
        $order = PtOrder::findOne([
            'id' => $order_detail->order_id,
            'is_delete' => 0,
            'user_id' => $this->user_id,
        ]);
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在，无法申请售后',
            ];
        }
        if ($order->is_pay != 1 && $order->pay_type != 2) {
            return [
                'code' => 1,
                'msg' => '订单尚未支付，无法申请售后',
            ];
        }

        $existRefund = PtOrderRefund::find()->where([
            'store_id' => $this->store_id,
            'user_id' => $this->user_id,
            'order_detail_id' => $this->order_detail_id,
            'is_delete' => 0,
        ])->one();
        if ($existRefund) {
            return [
                'code' => 1,
                'msg' => '该商品已申请过售后，请不要重复申请',
            ];
        }
        $refund = new PtOrderRefund();
        $refund->store_id = $this->store_id;
        $refund->user_id = $this->user_id;
        $refund->order_id = $order->id;
        $refund->order_detail_id = $order_detail->id;
        $refund->type = $this->type;
        $refund->order_refund_no = $this->getOrderRefundNo();
        if ($refund->type == 1) {
            $refund->refund_price = $order->pay_price;
        } elseif ($refund->type == 2) {
            $refund->refund_price = 0;
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
            $order->apply_delete = 1;
            $order->save();
            $form = new OrderWarn();
            $form->store_id = $order->store_id;
            $form->order_id = $order->id;
            $form->order_type = 2;
            $form->refund();
            return [
                'code' => 0,
                'msg' => '售后订单提交成功',
            ];
        } else {
            return $this->getErrorResponse($refund);
        }
    }

    /**
     * @return null|string
     */
    private function getOrderRefundNo()
    {
        $order_refund_no = null;
        while (true) {
            $order_refund_no = date('YmdHis') . mt_rand(100000, 999999);
            $exist_order_refund_no = PtOrderRefund::find()->where(['order_refund_no' => $order_refund_no])->exists();
            if (!$exist_order_refund_no) {
                break;
            }
        }
        return $order_refund_no;
    }
}
