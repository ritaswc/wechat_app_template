<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/8/27
 * Time: 10:45
 */

namespace app\modules\mch\models\order;


use app\models\ActivityMsgTpl;
use app\models\Order;
use app\models\OrderShare;
use app\models\Setting;
use app\models\User;
use app\models\UserShareMoney;
use app\modules\mch\models\MchModel;
use app\utils\TaskCreate;
use yii\db\ActiveRecord;

/**
 * @property ActiveRecord $order_model
 */
class OrderClerkForm extends MchModel
{
    public $order_model;
    public $order_id;
    public $store;

    public $order_type; //订单来源 0--商城订单 1--秒杀 2--拼团 3--预约 4--积分商城 5--九宫格 6--刮刮卡 7--砍价

    public $order;

    public $clerk_id;

    public function rules()
    {
        return [
            [['clerk_id', 'order_id'], 'integer']
        ];
    }

    public function clerk()
    {
        if (!$this->order_id) {
            return [
                'code' => 1,
                'msg' => '数据错误，请刷新后重试'
            ];
        }
        //订单核销
        $orderArr = [0, 1, 2, 4, 5, 6, 7];
        //预约核销
        $bookArr = [3];
        $orderClass = $this->order_model;
        $order = $orderClass::findOne(['id' => $this->order_id]);
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在，请刷新后重试'
            ];
        }
        if ($order->is_pay == 0 && $order->pay_type != 2) {
            return [
                'code' => 1,
                'msg' => '订单未支付'
            ];
        }
        $this->order = $order;

        if (in_array($this->order_type, $orderArr)) {
            return $this->order();
        } else if (in_array($this->order_type, $bookArr)) {
            return $this->book();
        } else {
            return [
                'code' => 1,
                'msg' => '数据错误，请刷新后重试'
            ];
        }
    }

    // 商城订单核销
    private function order()
    {
        $orderClass = $this->order_model;
        $order = $this->order;

        if($order->pay_type == 2){
            $order->is_pay = 1;
            $order->pay_time = time();
        }

        if (isset($order->is_send)) {
            $order->is_send = 1;
            $order->send_time = time();
        } else {
            return [
                'code' => 1,
                'msg' => "操作失败，{$orderClass}缺少is_send字段"
            ];
        }

        if (isset($order->is_confirm)) {
            $order->is_confirm = 1;
            $order->confirm_time = time();
        } else {
            return [
                'code' => 1,
                'msg' => "操作失败，{$orderClass}缺少is_confirm字段"
            ];
        }

        if (isset($order->clerk_id) || $order->clerk_id === null) {
            $order->clerk_id = $this->clerk_id;
            $clerk = User::findOne(['id' => $this->clerk_id, 'type' => 1, 'is_clerk' => 1, 'store_id' => $this->store->id]);
            if (!$clerk) {
                return [
                    'code' => 1,
                    'msg' => '核销员不存在，请重新选择'
                ];
            }
            $order->shop_id = $clerk->shop_id;
        } else {
            return [
                'code' => 1,
                'msg' => "操作失败，{$orderClass}缺少clerk_id字段"
            ];
        }

        if ($order->save()) {
            $msgTpl = new ActivityMsgTpl($order->user_id, 'ORDER_CLERK');
            $msgTpl->orderClerkTplMsg($order->order_no, '订单已核销');
            $orderType = [
                'STORE', 'MIAOSHA', 'PINTUAN', 'INTEGRAL', '', 'STORE', 'STORE', 'STORE'
            ];
            return [
                'code' => 0,
                'msg' => '操作成功'
            ];
        } else {
            return $this->getErrorResponse($order);
        }
    }

    private function book()
    {
        $orderClass = $this->order_model;
        $order = $this->order;

        if (isset($order->is_use)) {
            $order->is_use = 1;
            $order->use_time = time();
        } else {
            return [
                'code' => 1,
                'msg' => "操作失败，{$orderClass}缺少is_use字段"
            ];
        }

        if (isset($order->clerk_id) || $order->clerk_id === null) {
            $order->clerk_id = $this->clerk_id;
            $clerk = User::findOne(['id' => $this->clerk_id, 'type' => 1, 'is_clerk' => 1, 'store_id' => $this->store->id]);
            if (!$clerk) {
                return [
                    'code' => 1,
                    'msg' => '核销员不存在，请重新选择'
                ];
            }
            $order->shop_id = $clerk->shop_id;
        } else {
            return [
                'code' => 1,
                'msg' => "操作失败，{$orderClass}缺少clerk_id字段"
            ];
        }

        if ($order->save()) {
            $msgTpl = new ActivityMsgTpl($order->user_id, 'BOOK_CLERK');
            $msgTpl->orderClerkTplMsg($order->order_no, '订单已核销');
            $this->share_money_1($order->id, 1);
            return [
                'code' => 0,
                'msg' => '操作成功'
            ];
        } else {
            return $this->getErrorResponse($order);
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
        $share_setting = Setting::findOne(['store_id' => $this->store->id]);
        if ($share_setting->level == 0) {
            return false;
        }
        $order_share = OrderShare::findOne(['store_id' => $this->store->id, 'type' => $type, 'order_id' => $order_id]);
        if (!$order_share) {
            return false;
        }
        if ($order_share->rebate > 0) {
            $user = User::findOne(['id'=> $order_share->user_id]);
            $user->total_price += doubleval($order_share->rebate);
            $user->price += doubleval($order_share->rebate);
            $user->save();
            UserShareMoney::set($order_share->rebate, $user->id, $order_share->order_id, 0, 4, $this->store->id, 2);
        }
        //仅自购
        if ($share_setting->level == 4) {
            return false;
        }
        //一级佣金发放
        if ($share_setting->level > 1 || ($share_setting->level == 1 && $share_setting->is_rebate == 0)) {
            $user_1 = User::findOne($order_share->parent_id_1);
            if (!$user_1) {
                return false;
            }
            $user_1->total_price += doubleval($order_share->first_price);
            $user_1->price += doubleval($order_share->first_price);
            $user_1->save();
            UserShareMoney::set($order_share->first_price, $user_1->id, $order_id, 0, 1, $this->store->id, 3);
            $order_share->save();
        }
        //二级佣金发放
        if ($share_setting->level > 2 || ($share_setting->level == 2 && $share_setting->is_rebate == 0)) {
            $user_2 = User::findOne($order_share->parent_id_2);
            if (!$user_2) {
                return false;
            }
            $user_2->total_price += doubleval($order_share->second_price);
            $user_2->price += doubleval($order_share->second_price);
            $user_2->save();
            UserShareMoney::set($order_share->second_price, $user_2->id, $order_id, 0, 2, $this->store->id, 3);
        }
        //三级佣金发放
        if ($share_setting->level >= 3 && $share_setting->is_rebate == 0) {
            $user_3 = User::findOne($order_share->parent_id_3);
            if (!$user_3) {
                return false;
            }
            $user_3->total_price += doubleval($order_share->third_price);
            $user_3->price += doubleval($order_share->third_price);
            $user_3->save();
            UserShareMoney::set($order_share->third_price, $user_3->id, $order_id, 0, 3, $this->store->id, 3);
        }
        return true;
    }
}