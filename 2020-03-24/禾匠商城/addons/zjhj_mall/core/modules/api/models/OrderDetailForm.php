<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/28
 * Time: 19:16
 */

namespace app\modules\api\models;

use app\models\Goods;
use app\models\Level;
use app\models\MiaoshaGoods;
use app\models\MsGoods;
use app\models\MsOrder;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderRefund;
use app\models\Setting;
use app\models\Shop;
use app\models\Store;

class OrderDetailForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $order_id;
    public $order_no;

    public function rules()
    {
        return [
            [['order_id'], 'required'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $order = Order::findOne([
            'store_id' => $this->store_id,
            'user_id' => $this->user_id,
            'id' => $this->order_id,
            'is_delete' => 0,
            'is_recycle'=> 0,
        ]);
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在',
            ];
        }
        $status = "";
        if ($order->is_pay == 0) {
            $status = '订单未付款';
        } elseif ($order->is_pay == 1 && $order->is_send == 0) {
            $status = '订单待发货';
        } elseif ($order->is_send == 1 && $order->is_confirm == 0) {
            $status = '订单已发货';
        } elseif ($order->is_confirm == 1) {
            $status = '订单已完成';
        }

        $goods_list = OrderDetail::find()->alias('od')
            ->where(['od.order_id' => $order->id, 'od.is_delete' => 0])->with('goods')->all();
        $num = 0;
        foreach ($goods_list as $i => $item) {
            $newItem = [];
            if ($item['goods']['type'] == 5) {
                $newItem['goods_url'] = '/step/goods/goods?goods_id='. $item['goods_id'];
            } else {
                $newItem['goods_url'] = '/pages/goods/goods?id='. $item['goods_id'];
            }
            $newItem['goods_id'] = $item['goods_id'];
            $newItem['order_detail_id'] = $item['id'];
            $newItem['name'] = $item->goods->name;
            $newItem['total_price'] = $item['total_price'];
            $newItem['num'] = $item['num'];
            $newItem['attr'] = json_decode($item['attr']);
            $num += intval($item['num']);
            $newItem['goods_pic'] = $item['pic'] ?: Goods::getGoodsPicStatic($item['id'])->pic_url;
            $order_refund = OrderRefund::findOne([
                'order_detail_id' => $item['id'],
                'is_delete' => 0,
            ]);
            if ($order_refund) {
                $newItem['is_order_refund'] = 1;
            } else {
                $newItem['is_order_refund'] = 0;
            }
            if ($order->is_pay == 1 && $order->is_send == 1) {
                $newItem['order_refund_enable'] = 1;
            } else {
                $newItem['order_refund_enable'] = 0;
            }
            if ($order->is_confirm == 1) {
                $store = Store::findOne($this->store_id);
                if ((time() - $order->confirm_time) > $store->after_sale_time * 86400) {//超过可售后时间
                    $newItem['order_refund_enable'] = 0;
                }
            }
            if ($item['is_level'] == 1 && ($order->discount && $order->discount < 10)) {
                $newItem['is_level'] = 1;
            }
            $newList[] = $newItem;
        }
        if ($order->shop_id) {
            $shop = Shop::find()->select(['name', 'mobile', 'address', 'longitude', 'latitude'])->where(['store_id' => $this->store_id, 'id' => $order->shop_id])->asArray()->one();
        }
        if ($order->before_update_price) {
            if ($order->before_update_price < $order->pay_price) {
                $before_update = "加价";
                $money = $order->pay_price - $order->before_update_price;
            } else {
                $before_update = "优惠";
                $money = $order->before_update_price - $order->pay_price;
            }
        } else {
            $before_update = "";
            $money = "";
        }
        
        if (!$order->integral) {
            $order->integral = 0;
        }
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'order_id' => $order->id,
                'is_pay' => $order->is_pay,
                'is_send' => $order->is_send,
                'is_confirm' => $order->is_confirm,
                'status' => $status,
                'express' => $order->express,
                'express_no' => $order->express_no,
                'name' => $order->name,
                'mobile' => $order->mobile,
                'address' => $order->address,
                'order_no' => $order->order_no,
                'addtime' => date('Y-m-d H:i', $order->addtime),
                'total_price' => doubleval(sprintf('%.2f', $order->total_price)),
                'express_price' => doubleval(sprintf('%.2f', $order->express_price)),
                'goods_total_price' => doubleval(sprintf('%.2f', doubleval($order->total_price) - doubleval($order->express_price))),
                'coupon_sub_price' => $order->coupon_sub_price,
                'pay_price' => $order->pay_price,
                'num' => $num,
                'goods_list' => $newList,
                'is_offline' => $order->is_offline,
                'content' => $order->content ? $order->content : "",
                'before_update' => $before_update,
                'money' => $money,
                'shop' => $shop,
                'discount' => $order->discount,
                'user_coupon_id' => $order->user_coupon_id,
                'words' => $order->words,
                'pay_type' => $order->pay_type,
                'integral'=>\Yii::$app->serializer->decode($order->integral),
                'apply_delete' => $order->apply_delete
            ],
        ];
    }

    public function clerk()
    {
        if (stripos($this->order_no, 'M') > -1) {
            $order = MsOrder::find()->where([
                'store_id' => $this->store_id, 'order_no' => $this->order_no, 'is_delete' => 0
            ])->one();
            if (!$order) {
                return [
                    'code' => 1,
                    'msg' => '订单不存在-1',
                ];
            }
            $goods = MsGoods::find()->where(['id'=>$order->goods_id])->one();
            $newList[0]['attr'] = json_decode($order->attr, true);
            $newList[0]['goods_pic'] = $order->pic;
            $newList[0]['num'] = $order->num;
            $newList[0]['id'] = $goods->id;
            $newList[0]['name'] = $goods->name;
            $newList[0]['total_price'] = $order->total_price;

            $num = $order->num;
        } else {
            $order = Order::findOne([
                'store_id' => $this->store_id,
                'order_no' => $this->order_no,
                'is_delete' => 0,
            ]);
            if (!$order) {
                return [
                    'code' => 1,
                    'msg' => '订单不存在',
                ];
            }

            $goods_list = OrderDetail::find()->alias('od')
                ->where(['od.order_id' => $order->id, 'od.is_delete' => 0])->all();
            $num = 0;
            $newList = [];
            foreach ($goods_list as $i => $item) {
                $newItem = [];
                $newItem['goods_id'] = $item['goods_id'];
                $newItem['order_detail_id'] = $item['id'];
                $newItem['name'] = $item->goods->name;
                $newItem['total_price'] = $item['total_price'];
                $newItem['num'] = $item['num'];
                $newItem['attr'] = json_decode($item['attr']);
                $num += intval($item['num']);
                $newItem['goods_pic'] = Goods::getGoodsPicStatic($item['goods_id'])->pic_url;
                $order_refund = OrderRefund::findOne([
                    'order_detail_id' => $item['id'],
                    'is_delete' => 0,
                ]);
                if ($order_refund) {
                    $newItem['is_order_refund'] = 1;
                } else {
                    $newItem['is_order_refund'] = 0;
                }
                if ($order->is_pay == 1 && $order->is_send == 1) {
                    $newItem['order_refund_enable'] = 1;
                } else {
                    $newItem['order_refund_enable'] = 0;
                }
                if ($order->is_confirm == 1) {
                    $store = Store::findOne($this->store_id);
                    if ((time() - $order->confirm_time) > $store->after_sale_time * 86400) {//超过可售后时间
                        $newItem['order_refund_enable'] = 0;
                    }
                }
                $newList[] = $newItem;
            }
        }
        $status = "";
        if ($order->is_pay == 0) {
            $status = '订单未付款';
        } elseif ($order->is_pay == 1 && $order->is_send == 0) {
            $status = '订单待发货';
        } elseif ($order->is_send == 1 && $order->is_confirm == 0) {
            $status = '订单已发货';
        } elseif ($order->is_confirm == 1) {
            $status = '订单已完成';
        }

        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'order_id' => $order->id,
                'is_pay' => $order->is_pay,
                'is_send' => $order->is_send,
                'is_confirm' => $order->is_confirm,
                'status' => $status,
                'express' => $order->express,
                'express_no' => $order->express_no,
                'name' => $order->name,
                'mobile' => $order->mobile,
                'address' => $order->address,
                'order_no' => $order->order_no,
                'addtime' => date('Y-m-d H:i', $order->addtime),
                'total_price' => doubleval(sprintf('%.2f', $order->total_price)),
                'express_price' => doubleval(sprintf('%.2f', $order->express_price)),
                'goods_total_price' => doubleval(sprintf('%.2f', doubleval($order->total_price) - doubleval($order->express_price))),
                'coupon_sub_price' => $order->coupon_sub_price,
                'pay_price' => $order->pay_price,
                'num' => $num,
                'goods_list' => $newList,
                'is_offline' => $order->is_offline,
                'content' => $order->content ? $order->content : ""
            ],
        ];
    }
}
