<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/15
 * Time: 14:17
 */

namespace app\modules\mch\models;

use app\models\Goods;
use app\models\Model;
use app\models\MsGoods;
use app\models\MsOrder;
use app\models\Order;
use app\models\OrderDetail;
use app\models\Setting;
use app\models\User;
use app\modules\api\models\ShareMoneyForm;

class OrderPriceForm extends MchModel
{
    public $store_id;
    public $order_id;
    public $price;
    public $type;
    public $order_type;
    public $update_express;

    public function rules()
    {
        return [
            [['order_id', 'type'], 'number'],
            [['type'], 'in', 'range' => [1, 2]],
            [['order_type'], 'string'],
            [['price', 'update_express'], 'default', 'value' => 0],
            [['price', 'update_express'], 'number', 'min' => 0, 'max' => 999999],
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
                'code' => 0,
                'msg' => '网络异常'
            ];
        }
        $money = doubleval($order->pay_price);
        $total = doubleval($order->total_price);
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
            $order->total_price = round($total + $this->price + $this->update_express, 2);
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
            $order->total_price = round($total - $this->price - $this->update_express, 2);
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
            $this->setReturnData($order);
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

    /**
     * 设置佣金
     */
    private function setReturnData_1($order)
    {
        $setting = Setting::findOne(['store_id' => $order->store_id]);
        if (!$setting || $setting->level == 0) {
            return;
        }
        $user = User::findOne($order->user_id);//订单本人
        if (!$user) {
            return;
        }
        $order->parent_id = $user->parent_id;
        $parent = User::findOne($user->parent_id);//上级
        if ($parent->parent_id) {
            $order->parent_id_1 = $parent->parent_id;
            $parent_1 = User::findOne($parent->parent_id);//上上级
            if ($parent_1->parent_id) {
                $order->parent_id_2 = $parent_1->parent_id;
            } else {
                $order->parent_id_2 = -1;
            }
        } else {
            $order->parent_id_1 = -1;
            $order->parent_id_2 = -1;
        }
        $order_total = doubleval($order->total_price - $order->express_price);
        $pay_price = doubleval($order->pay_price - $order->express_price);

        if ($this->order_type == 'ms') {
            $goods = MsGoods::findOne(['id' => $order->goods_id, 'store_id' => $this->store_id]);
            $new_list = [];
            $new_list['total_price'] = $pay_price;
            $new_list['individual_share'] = $goods->individual_share;
            $new_list['share_commission_first'] = $goods->share_commission_first;
            $new_list['share_commission_second'] = $goods->share_commission_second;
            $new_list['share_commission_third'] = $goods->share_commission_third;
            $new_list['rebate'] = $goods->rebate;
            $new_list['num'] = $order->num;
            $order_detail_list[] = $new_list;
        } else {
            $order_detail_list = OrderDetail::find()->alias('od')->leftJoin(['g' => Goods::tableName()], 'od.goods_id=g.id')
                ->where(['od.is_delete' => 0, 'od.order_id' => $order->id])
                ->asArray()
                ->select(['od.*', 'g.*'])
                ->all();
        }
        $share_commission_money_first = 0;//一级分销总佣金
        $share_commission_money_second = 0;//二级分销总佣金
        $share_commission_money_third = 0;//三级分销总佣金
        $rebate = 0;//三级分销总佣金
        foreach ($order_detail_list as $item) {
            $item_price = doubleval($item['total_price']);
            if ($item['individual_share'] == 1) {
                $rate_first = doubleval($item['share_commission_first']);
                $rate_second = doubleval($item['share_commission_second']);
                $rate_third = doubleval($item['share_commission_third']);
                $rate_rebate = doubleval($item['rebate']);
                if ($item['share_type'] == 1) {
                    $share_commission_money_first += $rate_first * $item['num'];
                    $share_commission_money_second += $rate_second * $item['num'];
                    $share_commission_money_third += $rate_third * $item['num'];
                    $rebate += $rate_rebate * $item['num'];
                } else {
                    $share_commission_money_first += $item_price * $rate_first / 100;
                    $share_commission_money_second += $item_price * $rate_second / 100;
                    $share_commission_money_third += $item_price * $rate_third / 100;
                    $rebate += $item_price * $rate_rebate / 100;
                }
            } else {
                $rate_first = doubleval($setting->first);
                $rate_second = doubleval($setting->second);
                $rate_third = doubleval($setting->third);
                $rate_rebate = doubleval($setting->rebate);
                if ($setting->price_type == 1) {
                    $share_commission_money_first += $rate_first * $item['num'];
                    $share_commission_money_second += $rate_second * $item['num'];
                    $share_commission_money_third += $rate_third * $item['num'];
                    $rebate += $rate_rebate * $item['num'];
                } else {
                    $share_commission_money_first += $item_price * $rate_first / 100;
                    $share_commission_money_second += $item_price * $rate_second / 100;
                    $share_commission_money_third += $item_price * $rate_third / 100;
                    $rebate += $item_price * $rate_rebate / 100;
                }
            }
        }
        if ($user->is_distributor == 0) {
            $rebate = 0;
        }
        if ($setting->is_rebate == 0) {
            $rebate = 0;
        }


        $order->first_price = $share_commission_money_first < 0.01 ? 0 : $share_commission_money_first;
        $order->second_price = $share_commission_money_second < 0.01 ? 0 : $share_commission_money_second;
        $order->third_price = $share_commission_money_third < 0.01 ? 0 : $share_commission_money_third;
        $order->rebate = $rebate < 0.01 ? 0 : $rebate;
        $order->save();
    }
}
