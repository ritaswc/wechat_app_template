<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/3
 * Time: 15:26
 */

namespace app\modules\user\models\mch;

use app\modules\user\models\UserModel;
use app\models\MsOrder;
use app\models\Order;
use app\models\OrderDetail;

class OrderPriceForm extends UserModel
{

    public $store_id;
    public $mch_id;
    public $order_id;
    public $price;
    public $type;
    public $order_type;
    public $update_express;

    public function rules()
    {
        return [
            [['order_id', 'price', 'type', 'update_express'], 'number'],
            [['type'], 'in', 'range' => [1, 2]],
            [['order_type'], 'string'],
            [['price', 'update_express'], 'default', 'value' => 0],
            [['price', 'update_express'], 'number', 'min' => 0],
        ];
    }

    public function attributeLabels()
    {
        return [
            'price' => '修改的价格',
            'update_express' => '修改的价格',
        ];
    }

    public function update()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        if ($this->order_type == 'ms') {
            $order = MsOrder::findOne(['id' => $this->order_id, 'is_delete' => 0, 'is_pay' => 0,'mch_id'=>$this->mch_id]);
        } else {
            $order = Order::findOne(['id' => $this->order_id, 'is_delete' => 0, 'is_pay' => 0,'mch_id'=>$this->mch_id]);
        }
        if (!$order) {
            return [
                'code' => 0,
                'msg' => '网络异常'
            ];
        }
        $money = doubleval($order->pay_price);
        $express = doubleval($order->express_price);
        if ($order->before_update_price) {
        } else {
            $order->before_update_price = $money;
        }
        if ($order->before_update_express) {
        } else {
            $order->before_update_express = $express;
        }
        if ($this->price == 0 && $this->update_express == 0) {
            return [
                'code' => 1,
                'msg' => '请填写修改金额'
            ];
        }
        if ($this->type == 1) {
            $express_1 = $this->update_express + $express;
            $order->pay_price = round($money + $this->price + $this->update_express, 2);
        } else {
            if ($express < $this->update_express) {
                return [
                    'code' => 1,
                    'msg' => '优惠的运费不能超过原来的运费'
                ];
            }
            if ($money - $express - $this->price < 0.01) {
                return [
                    'code' => 1,
                    'msg' => '修改后的商品价格不能小于0.01'
                ];
            }
            $express_1 = $express - $this->update_express;
            $order->pay_price = round($money - $this->price - $this->update_express, 2);
        }
        $order->express_price = $express_1;
        if ($order->pay_price < 0.01) {
            $order->pay_price = 0.01;
            return [
                'code' => 1,
                'msg' => '修改后的价格不能小于0.01'
            ];
        }
        if ($order->save()) {
            $order_detail_list = OrderDetail::findAll(['order_id' => $order->id, 'is_delete' => 0]);
            $goods_total_price = 0.00;
            $goods_total_pay_price = $order->pay_price - $express_1;
            foreach ($order_detail_list as $goods) {
                $goods_total_price += $goods->total_price;
            }
            foreach ($order_detail_list as $goods) {
                $goods->total_price = doubleval(sprintf('%.2f', $goods_total_pay_price * $goods->total_price / $goods_total_price));
                $goods->save();
            }
            return [
                'code' => 0,
                'msg' => '成功'
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '网络异常'
            ];
        }
    }
}
