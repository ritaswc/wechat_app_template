<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 *
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/4/25
 * Time: 9:38
 */


namespace app\modules\api\models\mch;

use app\models\Goods;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderRefund;
use app\models\OrderUnion;
use app\models\User;
use app\models\UserAccountLog;
use app\modules\api\models\ApiModel;
use app\utils\Refund;
use yii\helpers\Html;

class OrderRefundForm extends ApiModel
{
    /** @var  integer $id refund id */
    public $id;
    public $mch_id;
    public $action;
    public $desc;

    public function rules()
    {
        return [
            [['id', 'action'], 'required'],
            ['desc', 'trim',],
            ['desc', 'string', 'max' => 1000,],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        /** @var OrderRefund $refund */
        $refund = OrderRefund::find()->alias('or')
            ->leftJoin(['o' => Order::tableName()], 'or.order_id=o.id')
            ->where([
                'or.id' => $this->id,
                'o.mch_id' => $this->mch_id,
            ])->select('or.*')->one();
        if (!$refund) {
            return [
                'code' => 1,
                'msg' => '售后订单不存在。'
            ];
        }
        if ($this->action == 'deny') {
            $refund->status = 3;
            $refund->refuse_desc = Html::encode($this->desc);
            $refund->save();
            return [
                'code' => 0,
                'msg' => '操作成功。',
            ];
        }
        if ($this->action == 'pass') {
            if ($refund->type == 1) {//退货退款
                return $this->moneyBack($refund);
            }
            if ($refund->type == 2) {//换货
                $refund->status = 2;
                $refund->save();
                return [
                    'code' => 0,
                    'msg' => '操作成功。',
                ];
            }
        }
    }


    /**
     * @param OrderRefund $refund
     * @return array
     */
    private function moneyBack($refund)
    {
        $order = Order::findOne($refund->order_id);
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在，退款失败。',
            ];
        }
        if ($order->is_delete != 0 && $order->is_cancel != 0) {
            return [
                'code' => 1,
                'msg' => '订单已删除或已取消，退款失败。',
            ];
        }
        if ($order->is_pay != 1) {
            return [
                'code' => 1,
                'msg' => '订单尚未付款，退款失败。',
            ];
        }
        $order_detail_list = OrderDetail::find()->where(['order_id' => $order->id, 'is_delete' => 0])->all();
        //库存恢复
        foreach ($order_detail_list as $order_detail) {
            $goods = Goods::findOne($order_detail->goods_id);
            $attr_id_list = [];
            foreach (json_decode($order_detail->attr) as $item) {
                array_push($attr_id_list, $item->attr_id);
            }
            $goods->numAdd($attr_id_list, $order_detail->num);
        }

        // 用户积分恢复
        $integral = json_decode($order->integral)->forehead_integral;
        $user = User::findOne(['id' => $order->user_id]);
        if ($integral > 0) {
            $user->integral += $integral;
            $user->save();
        }
        if ($order->pay_type == 1) {//微信支付
            if ($order->order_union_id) {
                $order_union = OrderUnion::findOne($order->order_union_id);
                if (!$order_union) {
                    return [
                        'code' => 1,
                        'msg' => '订单未找到，退款失败。',
                    ];
                }
                $out_trade_no = $order_union->order_no;
                $total_fee = $order_union->price;
                $res = Refund::refund($order_union,$refund->order_refund_no,$refund->refund_price);
                if($res !== true){
                    return $res;
                }
            } else {
                $out_trade_no = $order->order_no;
                $total_fee = $order->pay_price;
                $res = Refund::refund($order,$refund->order_refund_no,$refund->refund_price);
                if($res !== true){
                    return $res;
                }
            }
            $refund->status = 1;
            $refund->save();
            return [
                'code' => 0,
                'msg' => '操作成功。',
            ];
        }
        if ($order->pay_type == 2) {//货到付款，无需退款
            $refund->status = 1;
            $refund->save();
            return [
                'code' => 0,
                'msg' => '操作成功。',
            ];
        }
        if ($order->pay_type == 3) {//余额支付
            $user = User::findOne(['id' => $order->user_id]);
            if (!$user) {
                return [
                    'code' => 1,
                    'msg' => '找不到用户，退款失败。',
                ];
            }
            if ($user->money) {
                $user->money = $user->money + $refund->refund_price;
            } else {
                $user->money = $refund->refund_price;
            }
            $user->save();
            $refund->status = 1;
            $refund->save();
            $log = new UserAccountLog();
            $log->user_id = $user->id;
            $log->type = 1;
            $log->price = $refund->refund_price;
            $log->desc = "售后订单退款：退款订单号（{$refund->order_refund_no}）";
            $log->addtime = time();
            $log->order_type = 4;
            $log->order_id = $order->id;
            $log->save();
            return [
                'code' => 0,
                'msg' => '操作成功。',
            ];
        }
        return [
            'code' => 1,
            'msg' => '提交数据有误。',
        ];
    }
}
