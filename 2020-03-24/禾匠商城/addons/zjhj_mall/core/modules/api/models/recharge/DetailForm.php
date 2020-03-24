<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/5
 * Time: 16:16
 */

namespace app\modules\api\models\recharge;


use app\models\Goods;
use app\models\IntegralLog;
use app\models\MsGoods;
use app\models\MsOrder;
use app\models\MsOrderRefund;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderRefund;
use app\models\PondLog;
use app\models\PtGoods;
use app\models\PtOrder;
use app\models\PtOrderDetail;
use app\models\PtOrderRefund;
use app\models\Recharge;
use app\models\ReOrder;
use app\models\ScratchLog;
use app\models\YyGoods;
use app\modules\api\models\ApiModel;
use app\models\YyOrder;

class DetailForm extends ApiModel
{
    public $store_id;
    public $order_type;
    public $id;

    public function rules()
    {
        return [
            [['order_type', 'id'], 'trim'],
            [['id'], 'integer'],
            [['order_type'], 'string'],
            [['order_type'], 'in', 'range' => ['s', 'ms', 'pt', 'yy', 's_re', 'ms_re', 'pt_re', 'yy_re', 'r', 'log', 'pond', 'scratch']],
        ];
    }


    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        if ($this->order_type == 'r') {
            $data = $this->getRecharge();
        } elseif ($this->order_type == 's') {
            $data = $this->getOrder();
        } elseif ($this->order_type == 'ms') {
            $data = $this->getMsOrder();
        } elseif ($this->order_type == 'pt') {
            $data = $this->getPtOrder();
        } elseif ($this->order_type == 'yy') {
            $data = $this->getYyOrder();
        } elseif ($this->order_type == 's_re') {
            $data = $this->getOrderRefund();
        } elseif ($this->order_type == 'ms_re') {
            $data = $this->getOrderRefund();
        } elseif ($this->order_type == 'yy_re') {
            $data = $this->getYyOrderRefund();
        } elseif ($this->order_type == 'pt_re') {
            $data = $this->getOrderRefund();
        } elseif ($this->order_type == 'log') {
            $data = $this->getLog();
        } elseif ($this->order_type == 'pond') {
            $data = $this->getPond();
        } elseif ($this->order_type == 'scratch') {
            $data = $this->getScratch();
        } else {
            return [
                'code' => 1,
                'msg' => '系统异常'
            ];
        }
        return [
            'code' => 0,
            'msg' => '',
            'data' => $data
        ];
    }

    //充值
    public function getRecharge()
    {
        $list = ReOrder::find()->where(['store_id' => $this->store_id, 'id' => $this->id])->asArray()->one();
        $new_list = [];
        $new_list['time'] = date('Y-m-d H:i:s', $list['addtime']);
        $new_list['pay_price'] = '+' . $list['pay_price'];
        $new_list['send_price'] = '+' . $list['send_price'];
        $new_list['content'] = "充值";
        $new_list['order_no'] = $list['order_no'];
        $new_list['flag'] = 0;
        return $new_list;
    }

    //商城余额支付
    public function getOrder()
    {
        $new_list = [];
        $list = Order::find()->where([
            'store_id' => $this->store_id, 'id' => $this->id, 'is_pay' => 1, 'pay_type' => 3
        ])->asArray()->one();
        $goods_list = Goods::find()->alias('g')->where([
            'g.store_id' => $this->store_id
        ])->leftJoin(['od' => OrderDetail::tableName()], 'od.goods_id=g.id')
            ->andWhere(['od.order_id' => $list['id']])->select(['g.name'])->asArray()->column();
        $goods_str = implode(',', $goods_list);
        $new_list['time'] = date('Y-m-d H:i:s', $list['addtime']);
        $new_list['pay_price'] = '-' . $list['pay_price'];
        $new_list['send_price'] = $list['send_price'];
        $new_list['content'] = "消费-商城订单-" . $goods_str;
        $new_list['order_no'] = $list['order_no'];
        $new_list['flag'] = 1;
        return $new_list;
    }

    //秒杀余额支付
    public function getMsOrder()
    {
        $new_list = [];
        $list = MsOrder::find()->alias('o')->where([
            'o.store_id' => $this->store_id, 'o.id' => $this->id, 'o.is_pay' => 1, 'o.pay_type' => 3
        ])->leftJoin(['g' => MsGoods::tableName()], 'g.id=o.goods_id')
            ->select(['o.*', 'g.name'])->asArray()->one();
        $new_list['time'] = date('Y-m-d H:i:s', $list['addtime']);
        $new_list['pay_price'] = '-' . $list['pay_price'];
        $new_list['send_price'] = $list['send_price'];
        $new_list['content'] = "消费-秒杀订单-" . $list['name'];
        $new_list['order_no'] = $list['order_no'];
        $new_list['flag'] = 1;
        return $new_list;
    }

    //拼团余额支付
    public function getPtOrder()
    {
        $new_list = [];
        $list = PtOrder::find()->alias('o')->where([
            'o.store_id' => $this->store_id, 'o.id' => $this->id, 'o.is_pay' => 1, 'o.pay_type' => 3
        ])->leftJoin(['od' => PtOrderDetail::tableName()], 'od.order_id=o.id')
            ->leftJoin(['g' => PtGoods::tableName()], 'g.id=od.goods_id')
            ->select(['o.*', 'od.goods_id', 'g.name'])->asArray()->one();
        $new_list['time'] = date('Y-m-d H:i:s', $list['addtime']);
        $new_list['pay_price'] = '-' . $list['pay_price'];
        $new_list['send_price'] = $list['send_price'];
        $new_list['content'] = "消费-拼团订单-" . $list['name'];
        $new_list['order_no'] = $list['order_no'];
        $new_list['flag'] = 1;
        return $new_list;
    }
    //预约余额支付
    public function getYyOrder()
    {
        $new_list = [];
        $list = YyOrder::find()->alias('o')->where([
            'o.store_id' => $this->store_id, 'o.id' => $this->id, 'o.is_pay' => 1, 'o.pay_type' => 2
        ])->leftJoin(['g' => YyGoods::tableName()], 'g.id=o.goods_id')
            ->select(['o.*', 'g.name'])->asArray()->one();
        $new_list['time'] = date('Y-m-d H:i:s', $list['addtime']);
        $new_list['pay_price'] = '-' . $list['pay_price'];
        $new_list['send_price'] = $list['send_price'];
        $new_list['content'] = "消费-预约订单-" . $list['name'];
        $new_list['order_no'] = $list['order_no'];
        $new_list['flag'] = 1;
        return $new_list;
    }
    //商城订单退款
    public function getOrderRefund()
    {
        $new_list = [];
        $list = OrderRefund::find()->alias('ore')->where([
            'ore.store_id' => $this->store_id, 'ore.type' => 1, 'ore.status' => 1
        ])->leftJoin(['o' => Order::tableName()], 'o.id=ore.order_id')
            ->andWhere(['o.pay_type' => 3, 'ore.id' => $this->id])->select(['ore.*', 'o.order_no'])->asArray()->one();
        $new_list['time'] = date('Y-m-d H:i:s', $list['addtime']);
        $new_list['pay_price'] = '+' . $list['refund_price'];
        $new_list['send_price'] = $list['send_price'];
        $new_list['content'] = "消费-商城订单退款";
        $new_list['order_no'] = $list['order_no'];
        $new_list['order_refund_no'] = $list['order_refund_no'];
        $new_list['flag'] = 0;
        return $new_list;
    }

    //秒杀退款
    public function getMsOrderRefund()
    {
        $new_list = [];
        $list = MsOrderRefund::find()->alias('ore')->where([
            'ore.store_id' => $this->store_id, 'ore.type' => 1, 'ore.status' => 1
        ])->leftJoin(['o' => MsOrder::tableName()], 'o.id=ore.order_id')
            ->andWhere(['o.pay_type' => 3, 'ore.id' => $this->id])->select(['ore.*', 'o.order_no'])->asArray()->one();
        $new_list['time'] = date('Y-m-d H:i:s', $list['addtime']);
        $new_list['pay_price'] = '+' . $list['refund_price'];
        $new_list['send_price'] = $list['send_price'];
        $new_list['content'] = "消费-秒杀订单退款";
        $new_list['order_no'] = $list['order_no'];
        $new_list['order_refund_no'] = $list['order_refund_no'];
        $new_list['flag'] = 0;
        return $new_list;
    }

    //拼团退款
    public function getPtOrderRefund()
    {
        $new_list = [];
        $list = PtOrderRefund::find()->alias('ore')->where([
            'ore.store_id' => $this->store_id, 'ore.type' => 1, 'ore.status' => 1
        ])->leftJoin(['o' => PtOrder::tableName()], 'o.id=ore.order_id')
            ->andWhere(['o.pay_type' => 3, 'ore.id' => $this->id])->asArray()->one();
        $new_list['time'] = date('Y-m-d H:i:s', $list['addtime']);
        $new_list['pay_price'] = '+' . $list['refund_price'];
        $new_list['send_price'] = $list['send_price'];
        $new_list['content'] = "消费-拼团订单退款";
        $new_list['order_no'] = $list['order_no'];
        $new_list['order_refund_no'] = $list['order_refund_no'];
        $new_list['flag'] = 0;
        return $new_list;
    }

    //预约退款
    public function getYyOrderRefund()
    {
        $new_list = [];
        $list = YyOrder::find()->alias('ore')->where([
            'ore.store_id' => $this->store_id, 'ore.is_pay' => 1, 'is_refund' => 1, 'ore.pay_type' => 2, 'ore.id' => $this->id
        ])->asArray()->one();
        $new_list['time'] = date('Y-m-d H:i:s', $list['refund_time']);
        $new_list['pay_price'] = '+' . $list['pay_price'];
        $new_list['send_price'] = $list['send_price'];
        $new_list['content'] = "消费-预约订单退款";
        $new_list['order_no'] = $list['order_no'];
        $new_list['order_refund_no'] = $list['order_refund_no'];
        $new_list['flag'] = 0;
        return $new_list;
    }

    // 后台充值
    public function getLog()
    {
        $newList = [];
        $list = IntegralLog::find()->where(['id' => $this->id, 'type' => 1])->one();
        $new_list['time'] = date('Y-m-d H:i:s', $list['addtime']);
        if(strpos($list['content'],'充值') === false) {
            $new_list['pay_price'] = '-' . $list['integral'];
            $new_list['send_price'] = 0;
            $new_list['content'] = "扣除-后台扣除";
        }else{
            $new_list['pay_price'] = '+' . $list['integral'];
            $new_list['send_price'] = 0;
            $new_list['content'] = "充值-后台充值";
        }
        $new_list['flag'] = 0;
        return $new_list;
    }

    // 九宫格抽奖
    private function getPond()
    {
        $list = PondLog::find()->where(['id' => $this->id, 'type' => 1, 'status' => 1])->one();
        $new_list['time'] = date('Y-m-d H:i:s', $list['create_time']);
        $new_list['pay_price'] = '+' . $list['price'];
        $new_list['send_price'] = 0;
        $new_list['content'] = "九宫格抽奖-获得";
        $new_list['flag'] = 0;
        return $new_list;
    }

    // 刮刮卡
    private function getScratch()
    {
        $list = ScratchLog::find()->where(['id' => $this->id, 'type' => 1, 'status' => 2])->one();
        $new_list['time'] = date('Y-m-d H:i:s', $list['create_time']);
        $new_list['pay_price'] = '+' . $list['price'];
        $new_list['send_price'] = 0;
        $new_list['content'] = "刮刮卡-获得";
        $new_list['flag'] = 0;
        return $new_list;
    }
}
