<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/8/30
 * Time: 13:57
 */

namespace app\modules\mch\models\order;

use app\models\MsOrder;
use app\models\Order;
use app\models\OrderDetail;
use app\modules\api\models\ShareMoneyForm;
use app\modules\mch\models\MchModel;

class OrderPriceForm extends MchModel
{
    public $store_id;

    public $order_id;

    public $pay_price;
    public $express_price;

    public $order_type;

    public function rules()
    {
        return [
            [['pay_price', 'express_price'], 'number'],
            [['order_type'],'string'],
            [['order_id'],'integer']
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        if ($this->order_type == 'ms') {
            $order = MsOrder::findOne(['id' => $this->order_id, 'is_delete' => 0, 'is_pay' => 0]);
        } else {
            $order = Order::findOne([
                'id' => $this->order_id,
                'is_delete' => 0,
                'is_pay' => 0,
                'mch_id' => 0,]);
        }

        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单错误'
            ];
        }

        $money = doubleval($order->pay_price);
        $express = doubleval($order->express_price);

        if ($this->express_price || $this->express_price == 0) {
            if ($this->express_price < 0) {
                return [
                    'code' => 1,
                    'msg' => '运费不能小于0'
                ];
            }

            if ($order->before_update_express) {
            } else {
                $order->before_update_express = $express;
            }

            $order->express_price = $this->express_price;
        }

        if ($this->pay_price || $this->pay_price == 0) {
            if ($this->pay_price < 0) {
                return [
                    'code' => 1,
                    'msg' => '支付价格不能小于0'
                ];
            }

            if ($order->before_update_price) {
            } else {
                $order->before_update_price = $money;
            }
            $order->pay_price = $this->pay_price;
        }

        if ($order->save()) {
            if ($this->order_type == 'ms') {
                $orderDetailList = [$order];
            } else {
                $orderDetailList = OrderDetail::findAll(['order_id' => $order->id, 'is_delete' => 0]);
            }
            $goodsTotalPrice = 0.00;
            $goodsTotalPayPrice = $order->pay_price - $order->express_price;
            foreach ($orderDetailList as $goods) {
                $goodsTotalPrice += $goods->total_price;
            }
            foreach ($orderDetailList as $goods) {
                if (in_array(get_plugin_type(), [0,2])) {
                    $goods->total_price = doubleval(sprintf('%.2f', $goodsTotalPayPrice * $goods->total_price / $goodsTotalPrice));
                }
                $goods->save();
            }
            $this->setReturnData($order);
            return [
                'code' => 0,
                'msg' => '成功'
            ];
        } else {
            return [
                'code' => 1,
                'msg' => $this->getErrorResponse($order)
            ];
        }
    }

    /**
     * 设置佣金
     */
    private function setReturnData($order)
    {
        $form = new ShareMoneyForm();
        $form->order = $order;
        if ($this->order_type == 'ms') {
            $form->order_type = 1;
        } else {
            $form->order_type = 0;
        }
        return $form->setData();
    }
}
