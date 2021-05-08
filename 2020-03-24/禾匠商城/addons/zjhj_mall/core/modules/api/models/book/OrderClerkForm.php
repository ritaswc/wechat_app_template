<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/8
 * Time: 17:20
 */

namespace app\modules\api\models\book;

use app\models\ActivityMsgTpl;
use app\models\Order;
use app\models\OrderShare;
use app\models\Setting;
use app\models\User;
use app\models\UserShareMoney;
use app\models\YyOrder;
use app\modules\api\models\ApiModel;

/**
 * Class OrderClerkForm
 * @package app\modules\api\models\book
 * 预约订线下核销
 */
class OrderClerkForm extends ApiModel
{
    public $order_id;
    public $store_id;
    public $user_id;

    /**
     * @return array
     * 预约订单线下核销
     * 逻辑操作
     */
    public function save()
    {
        $order = YyOrder::findOne(['id' => $this->order_id, 'store_id' => $this->store_id, 'is_pay' => 1]);
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '网络异常-1'
            ];
        }
        if ($order->apply_delete == 1 && $order->is_refund == 0) {
            return [
                'code' => 1,
                'msg' => '订单正在申请退款'
            ];
        }
        if ($order->apply_delete == 1 && $order->is_refund == 1) {
            return [
                'code' => 1,
                'msg' => '订单已退款'
            ];
        }
        $user = User::findOne(['id' => $this->user_id]);
        if ($user->is_clerk == 0) {
            return [
                'code' => 1,
                'msg' => '不是核销员'
            ];
        }
        if ($order->is_use == 1) {
            return [
                'code' => 1,
                'msg' => '订单已核销'
            ];
        }
        $order->clerk_id = $user->id;
        $order->shop_id = $user->shop_id;
        $order->is_use = 1;
        $order->use_time = time();

        if ($order->save()) {
            $this->share_money_1($order->id, 1);

            $msgTpl = new ActivityMsgTpl($order->user_id, 'ORDER_CLERK');
            $msgTpl->orderClerkTplMsg($order->order_no, '订单已核销');
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
     * @param $order_id
     * @param int $type
     * @return bool
     * 佣金发放  预约
     */
    private function share_money_1($order_id, $type = 0)
    {
        $share_setting = Setting::findOne(['store_id' => $this->store_id]);
        if ($share_setting->level == 0) {
            return false;
        }
        $order_share = OrderShare::findOne(['store_id' => $this->store_id, 'type' => $type, 'order_id' => $order_id]);
        if (!$order_share) {
            return false;
        }
        if ($order_share->rebate > 0) {
            $user = User::findOne(['id'=> $order_share->user_id]);
            $user->total_price += doubleval($order_share->rebate);
            $user->price += doubleval($order_share->rebate);
            $user->save();
            UserShareMoney::set($order_share->rebate, $user->id, $order_share->order_id, 0, 4, $this->store_id, 2);
        }
        //仅自购
        if ($share_setting->level == 4) {
            return false;
        }
        //一级佣金发放
        if ($share_setting->level >= 1) {
            $user_1 = User::findOne($order_share->parent_id_1);
            if (!$user_1) {
                return false;
            }
            $user_1->total_price += doubleval($order_share->first_price);
            $user_1->price += doubleval($order_share->first_price);
            $user_1->save();
            UserShareMoney::set($order_share->first_price, $user_1->id, $order_id, 0, 1, $this->store_id, 3);
            $order_share->save();
        }
        //二级佣金发放
        if ($share_setting->level >= 2) {
            $user_2 = User::findOne($order_share->parent_id_2);
            if (!$user_2) {
                return false;
            }
            $user_2->total_price += doubleval($order_share->second_price);
            $user_2->price += doubleval($order_share->second_price);
            $user_2->save();
            UserShareMoney::set($order_share->second_price, $user_2->id, $order_id, 0, 2, $this->store_id, 3);
        }
        //三级佣金发放
        if ($share_setting->level >= 3) {
            $user_3 = User::findOne($order_share->parent_id_3);
            if (!$user_3) {
                return false;
            }
            $user_3->total_price += doubleval($order_share->third_price);
            $user_3->price += doubleval($order_share->third_price);
            $user_3->save();
            UserShareMoney::set($order_share->third_price, $user_3->id, $order_id, 0, 3, $this->store_id, 3);
        }
        return true;
    }
}
