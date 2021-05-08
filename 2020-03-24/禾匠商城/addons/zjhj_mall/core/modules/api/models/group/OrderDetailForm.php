<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/28
 * Time: 19:16
 */

namespace app\modules\api\models\group;

use app\models\Goods;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderRefund;
use app\models\PtGoods;
use app\models\PtOrder;
use app\models\PtOrderDetail;
use app\models\PtOrderRefund;
use app\models\Setting;
use app\models\Shop;
use app\models\Store;
use app\modules\api\models\ApiModel;

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
        $order = PtOrder::findOne([
            'store_id' => $this->store_id,
            'user_id' => $this->user_id,
            'id' => $this->order_id,
            'is_delete' => 0,
        ]);
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在',
            ];
        }
        $status = [
            1=> '待付款',
            2=> '拼团中',
            3=> '拼团成功',
            4=> '拼团失败',
        ];
        $goods_list = PtOrderDetail::find()->alias('od')
            ->leftJoin(['g' => PtGoods::tableName()], 'od.goods_id=g.id')
            ->select('g.id,od.id AS order_detail_id,g.name,od.attr,od.num,od.total_price,od.pic')
            ->where(['od.order_id' => $order->id, 'od.is_delete' => 0])->asArray()->all();
        $num = 0;
        foreach ($goods_list as $i => $item) {
            $goods_list[$i]['attr'] = json_decode($item['attr']);
            $num += intval($item['num']);
            $goods_list[$i]['goods_pic'] = $item['pic']?:PtGoods::getGoodsPicStatic($item['id'])->pic_url;

            $order_refund = PtOrderRefund::findOne([
                'order_detail_id' => $item['order_detail_id'],
                'is_delete' => 0,
            ]);
            $goods_list[$i]['order_refund_enable'] = 1;

            if ($order_refund) {
                $goods_list[$i]['is_order_refund'] = 1;
            } else {
                $goods_list[$i]['is_order_refund'] = 0;
            }

            if ($order->is_confirm == 1) {
                $store = Store::findOne($this->store_id);
                if ((time() - $order->confirm_time) > $store->after_sale_time * 86400) {//超过可售后时间
                    $goods_list[$i]['order_refund_enable'] = 0;
                }
            }
        }

        $limit_time_res = [
            'days'  => '00',
            'hours' => '00',
            'mins'  => '00',
            'secs'  => '00',
        ];
        $timediff = $order->limit_time - time();
        $groupFail = 0;
        if ($timediff<=0) {
            $groupFail = 1;     // 拼团失败
        }
        $limit_time_res['days'] = intval($timediff/86400)>0?intval($timediff/86400):0;
        //计算小时数
        $remain = $timediff%86400;
        $limit_time_res['hours'] = intval($remain/3600)>0?intval($remain/3600):0;
        //计算分钟数
        $remain = $remain%3600;
        $limit_time_res['mins'] = intval($remain/60)>0?intval($remain/60):0;
        //计算秒数
        $limit_time_res['secs'] = $remain%60>0?$remain%60:0;
        $limit_time_ms = explode('-', date('Y-m-d-H-i-s', $order->limit_time));

        if ($order->shop_id) {
            $shop = Shop::find()->select(['name','mobile','address','longitude','latitude'])->where(['store_id'=>$this->store_id,'id'=>$order->shop_id])->asArray()->one();
        }

//        // 验证已完成订单是否超过售后时间
//        $inAfterSaleTime = true;
//        if ($order->is_confirm == 1){
//            $after_sale_time = $order->confirm_time + ($store->after_sale_time * 3600);
//            $onlyTime = $after_sale_time - time();
//            if ($onlyTime <= 0){
//                $inAfterSaleTime = false;   // 订单超过售后时间
//            }
//        }

        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'order_id' => $order->id,
                'is_pay' => $order->is_pay,
                'is_send' => $order->is_send,
                'is_confirm' => $order->is_confirm,
                'status' => $order->status,
                'status_name' => $status[$order->status],
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
                'pay_price' => $order->pay_price,
                'num' => $num,
                'goods_list' => $goods_list,
                'is_group' => $order->is_group,
                'offline'=>$order->offline,
                'shop'=>$shop,
                'colonel'=>$order->is_group==1?$order->colonel:0,
                'limit_time'=>$limit_time_res,
                'limit_time_ms'=>$limit_time_ms,
                'apply_delete'=>$order->apply_delete,
//                'inAfterSaleTime'=>$inAfterSaleTime,
                'content'=>$order->content,
                'words'=>$order->words,
                'is_cancel'=>$order->is_cancel,
            ],
        ];
    }
//    public function clerk()
//    {
//        $order = Order::findOne([
//            'store_id' => $this->store_id,
//            'order_no' => $this->order_no,
//            'is_delete' => 0,
//        ]);
//        if (!$order)
//            return [
//                'code' => 1,
//                'msg' => '订单不存在',
//            ];
//        $status = "";
//        if ($order->is_pay == 0) {
//            $status = '订单未付款';
//        } elseif ($order->is_pay == 1 && $order->is_send == 0) {
//            $status = '订单待发货';
//        } elseif ($order->is_send == 1 && $order->is_confirm == 0) {
//            $status = '订单已发货';
//        } elseif ($order->is_confirm == 1) {
//            $status = '订单已完成';
//        }
//
//        $goods_list = OrderDetail::find()->alias('od')->leftJoin(['g' => Goods::tableName()], 'od.goods_id=g.id')
//            ->select('g.id,od.id AS order_detail_id,g.name,od.attr,od.num,od.total_price')
//            ->where(['od.order_id' => $order->id, 'od.is_delete' => 0])->asArray()->all();
//        $num = 0;
//        foreach ($goods_list as $i => $item) {
//            $goods_list[$i]['attr'] = json_decode($item['attr']);
//            $num += intval($item['num']);
//            $goods_list[$i]['goods_pic'] = Goods::getGoodsPicStatic($item['id'])->pic_url;
//            $order_refund = OrderRefund::findOne([
//                'order_detail_id' => $item['order_detail_id'],
//                'is_delete' => 0,
//            ]);
//            if ($order_refund) {
//                $goods_list[$i]['is_order_refund'] = 1;
//            } else {
//                $goods_list[$i]['is_order_refund'] = 0;
//            }
//            if ($order->is_pay == 1 && $order->is_send == 1) {
//                $goods_list[$i]['order_refund_enable'] = 1;
//            } else {
//                $goods_list[$i]['order_refund_enable'] = 0;
//            }
//            if ($order->is_confirm == 1) {
//                $store = Store::findOne($this->store_id);
//                if ((time() - $order->confirm_time) > $store->after_sale_time * 86400) {//超过可售后时间
//                    $goods_list[$i]['order_refund_enable'] = 0;
//                }
//            }
//
//        }
//
//        return [
//            'code' => 0,
//            'msg' => 'success',
//            'data' => [
//                'order_id' => $order->id,
//                'is_pay' => $order->is_pay,
//                'is_send' => $order->is_send,
//                'is_confirm' => $order->is_confirm,
//                'status' => $status,
//                'express' => $order->express,
//                'express_no' => $order->express_no,
//                'name' => $order->name,
//                'mobile' => $order->mobile,
//                'address' => $order->address,
//                'order_no' => $order->order_no,
//                'addtime' => date('Y-m-d H:i', $order->addtime),
//                'total_price' => doubleval(sprintf('%.2f', $order->total_price)),
//                'express_price' => doubleval(sprintf('%.2f', $order->express_price)),
//                'goods_total_price' => doubleval(sprintf('%.2f', doubleval($order->total_price) - doubleval($order->express_price))),
//                'coupon_sub_price' => $order->coupon_sub_price,
//                'pay_price' => $order->pay_price,
//                'num' => $num,
//                'goods_list' => $goods_list,
//                'is_offline'=>$order->is_offline,
//                'content'=>$order->content?$order->content:""
//            ],
//        ];
//    }
}
