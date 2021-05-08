<?php

namespace app\models\common\admin\order;

use app\models\Goods;
use app\models\Model;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderRefund;
use Yii;

use app\models\PtGoods;
use app\models\YyGoods;
use app\models\MsGoods;
use app\models\IntegralGoods;

use app\models\PtOrder;
use app\models\MsOrder;
use app\models\YyOrder;
use app\models\IntegralOrder;

use app\models\PtOrderDetail;
use app\models\YyOrderDetail;

use app\models\PtOrderRefund;
use app\models\MsOrderRefund;
use app\models\IntegralOrderDetail;


class CommonOrderPlugStats extends Model
{
    /**
     * 获取订单商品总数(去除申请退款成功的)
     * @param null $startTime
     * @param null $endTime
     * @param int $mchId
     * @return int
     */
    public function getOrderGoodsCount($table,$startTime = null, $endTime = null)
    {
        switch ($table){
            case 'pt':
                $query = PtOrder::find()->where([
                    'is_delete' => Order::IS_DELETE_FALSE,
                    'is_cancel' => Order::IS_CANCEL_FALSE,
                    'store_id' => $this->getCurrentStoreId(),
                ])->andWhere(['or', ['is_pay' => Order::IS_PAY_TRUE], ['pay_type' => Order::PAY_TYPE_COD]]);      

                if ($startTime !== null) {
                    $query->andWhere(['>=', 'addtime', $startTime]);
                }
                if ($endTime !== null) {
                    $query->andWhere(['<=', 'addtime', $endTime]);
                }

                $orders = $query->with('orderDetail', 'refund')->all();

                $goodsCount = 0;
                $refund = 0;
                $orderDetailIds = [];


                foreach ($orders as $item) {
                    foreach ($item->refund as $i) {
                        if ($i->status == OrderRefund::STATUS_REFUND_AGREE) {
                            $orderDetailIds[] = $i->order_detail_id;
                        }
                    }
                }
                foreach ($orders as $item) {
                    foreach ($item->orderDetail as $i) {
                        $goodsCount = $goodsCount + $i->num;
                        if (in_array($i->id, $orderDetailIds)) {
                            $refund = $refund + $i->num;
                        }
                    }
                }
                break;
            case 'ms':
                $query = MsOrder::find()->where([
                    'is_delete' => Order::IS_DELETE_FALSE,
                    'is_cancel' => Order::IS_CANCEL_FALSE,
                    'store_id' => $this->getCurrentStoreId(),
                ])->andWhere(['or', ['is_pay' => Order::IS_PAY_TRUE], ['pay_type' => Order::PAY_TYPE_COD]]);      

                if ($startTime !== null) {
                    $query->andWhere(['>=', 'addtime', $startTime]);
                }
                if ($endTime !== null) {
                    $query->andWhere(['<=', 'addtime', $endTime]);
                }

                $orders = $query->with('orderDetail', 'refund')->all();

                $goodsCount = 0;
                $refund = 0;
                $orderDetailIds = [];


                foreach ($orders as $item) {
                    foreach ($item->refund as $i) {
                        if ($i->status == OrderRefund::STATUS_REFUND_AGREE) {
                            $orderDetailIds[] = $i->order_id;
                        }
                    }
                }
                foreach ($orders as $item) {
                    foreach ($item->orderDetail as $i) {
                        $goodsCount = $goodsCount + $i->num;
                        if (in_array($i->id, $orderDetailIds)) {
                            $refund = $refund + $i->num;
                        }
                    }
                }

                break;
            case 'yy':
                $query = YyOrder::find()->where([
                    'is_delete' => Order::IS_DELETE_FALSE,
                    'is_cancel' => Order::IS_CANCEL_FALSE,
                    'store_id' => $this->getCurrentStoreId(),
                ])->andWhere(['<>','is_refund',1])->andWhere(['or', ['is_pay' => Order::IS_PAY_TRUE], ['pay_type' => Order::PAY_TYPE_COD]]);      

                if ($startTime !== null) {
                    $query->andWhere(['>=', 'addtime', $startTime]);
                }
                if ($endTime !== null) {
                    $query->andWhere(['<=', 'addtime', $endTime]);
                }

                $goodsCount = $query->count();
                $refund = 0;

                break;     
            case 'kj':
                $query = Order::find()->where([
                    'is_delete' => Order::IS_DELETE_FALSE,
                    'is_cancel' => Order::IS_CANCEL_FALSE,
                    'type' => 2,
                    'store_id' => $this->getCurrentStoreId(),
                ])->andWhere(['or', ['is_pay' => Order::IS_PAY_TRUE], ['pay_type' => Order::PAY_TYPE_COD]]);

                if ($startTime !== null) {
                    $query->andWhere(['>=', 'addtime', $startTime]);
                }
                if ($endTime !== null) {
                    $query->andWhere(['<=', 'addtime', $endTime]);
                }

                $orders = $query->with('detail', 'refund')->all();
                $goodsCount = 0;
                $refund = 0;
                $orderDetailIds = [];

                // 退款可能存在同一个商品，存在多个数量，通过 order_detail_id 去订单详情表查询
                foreach ($orders as $item) {
                    foreach ($item->refund as $i) {
                        if ($i->status == OrderRefund::STATUS_REFUND_AGREE) {
                            $orderDetailIds[] = $i->order_detail_id;
                        }
                    }
                }

                foreach ($orders as $item) {
                    foreach ($item->detail as $i) {
                        $goodsCount = $goodsCount + $i->num;

                        if (in_array($i->id, $orderDetailIds)) {
                            $refund = $refund + $i->num;
                        }
                    }
                }

                break;
            case 'jf':
                $query = IntegralOrder::find()->where([
                    'is_delete' => Order::IS_DELETE_FALSE,
                    'is_cancel' => Order::IS_CANCEL_FALSE,
                    'store_id' => $this->getCurrentStoreId(),
                    'apply_delete'=> 0
                ])->andWhere(['or', ['is_pay' => Order::IS_PAY_TRUE], ['pay_type' => Order::PAY_TYPE_COD]]);

                if ($startTime !== null) {
                    $query->andWhere(['>=', 'addtime', $startTime]);
                }
                if ($endTime !== null) {
                    $query->andWhere(['<=', 'addtime', $endTime]);
                }

                $orders = $query->with('detail')->all();

                $goodsCount = 0;
                $refund = 0;

                foreach ($orders as $item) {
                    $goodsCount = $goodsCount + $item->detail->num;
                }
                break;
            case 'mch':
                $query = Order::find()->where(['and', [
                    'is_delete' => Order::IS_DELETE_FALSE,
                    'is_cancel' => Order::IS_CANCEL_FALSE,
                    'store_id' => $this->getCurrentStoreId(),
                ], ['<>', 'mch_id', 0]])->andWhere(['or', ['is_pay' => Order::IS_PAY_TRUE], ['pay_type' => Order::PAY_TYPE_COD]]);

                if ($startTime !== null) {
                    $query->andWhere(['>=', 'addtime', $startTime]);
                }
                if ($endTime !== null) {
                    $query->andWhere(['<=', 'addtime', $endTime]);
                }

                $orders = $query->with('detail', 'refund')->all();
                $goodsCount = 0;
                $refund = 0;
                $orderDetailIds = [];

                // 退款可能存在同一个商品，存在多个数量，通过 order_detail_id 去订单详情表查询
                foreach ($orders as $item) {
                    foreach ($item->refund as $i) {
                        if ($i->status == OrderRefund::STATUS_REFUND_AGREE) {
                            $orderDetailIds[] = $i->order_detail_id;
                        }
                    }
                }

                foreach ($orders as $item) {
                    foreach ($item->detail as $i) {
                        $goodsCount = $goodsCount + $i->num;

                        if (in_array($i->id, $orderDetailIds)) {
                            $refund = $refund + $i->num;
                        }
                    }
                };
                break;
            default:
               return;
        }

        return intval($goodsCount - $refund);
    }


    /**
     * 获取订单金额总数（实际付款）
     * @param null $startTime
     * @param null $endTime
     * @param int $mchId
     * @return string
     */
    public function getOrderPriceCount($table, $startTime = null, $endTime = null)
    {
        switch ($table){
            case 'pt':
                $query = PtOrder::find()->andWhere([
                    'is_delete' => Order::IS_DELETE_FALSE,
                    'is_cancel' => Order::IS_CANCEL_FALSE,
                    'store_id' => $this->getCurrentStoreId(),
                ])->andWhere(['or', ['is_pay' => Order::IS_PAY_TRUE], ['pay_type' => Order::PAY_TYPE_COD]]);

                if ($startTime !== null) {
                    $query->andWhere(['>=', 'addtime', $startTime]);
                }
                if ($endTime !== null) {
                    $query->andWhere(['<=', 'addtime', $endTime]);
                }
                $orders = $query->with(['refund' => function ($query) {
                    $query->andWhere(['type' => OrderRefund::TYPE_REFUND, 'status' => OrderRefund::STATUS_REFUND_AGREE]);
                }])->all();

                $totalPrice = 0;
                $refundPrice = 0;
                foreach ($orders as $item) {
                    $totalPrice = $totalPrice + $item->pay_price;
                    foreach ($item->refund as $i) {
                        $refundPrice = $refundPrice + $i->refund_price;
                    }
                }
                break;
            case 'ms':
                $query = MsOrder::find()->andWhere([
                    'is_delete' => Order::IS_DELETE_FALSE,
                    'is_cancel' => Order::IS_CANCEL_FALSE,
                    'store_id' => $this->getCurrentStoreId(),
                ])->andWhere(['or', ['is_pay' => Order::IS_PAY_TRUE], ['pay_type' => Order::PAY_TYPE_COD]]);

                if ($startTime !== null) {
                    $query->andWhere(['>=', 'addtime', $startTime]);
                }
                if ($endTime !== null) {
                    $query->andWhere(['<=', 'addtime', $endTime]);
                }
                $orders = $query->with(['refund' => function ($query) {
                    $query->andWhere(['type' => OrderRefund::TYPE_REFUND, 'status' => OrderRefund::STATUS_REFUND_AGREE]);
                }])->all();

                $totalPrice = 0;
                $refundPrice = 0;
                foreach ($orders as $item) {
                    $totalPrice = $totalPrice + $item->pay_price;
                    foreach ($item->refund as $i) {
                        $refundPrice = $refundPrice + $i->refund_price;
                    }
                }

                break;
            case 'yy':
                $query = YyOrder::find()->where([
                    'is_delete' => Order::IS_DELETE_FALSE,
                    'is_cancel' => Order::IS_CANCEL_FALSE,
                    'store_id' => $this->getCurrentStoreId(),
                ])->andWhere(['<>','is_refund',1])->andWhere(['or', ['is_pay' => Order::IS_PAY_TRUE], ['pay_type' => Order::PAY_TYPE_COD]]);      


                if ($startTime !== null) {
                    $query->andWhere(['>=', 'addtime', $startTime]);
                }
                if ($endTime !== null) {
                    $query->andWhere(['<=', 'addtime', $endTime]);
                }

                $orders = $query->all();

                $totalPrice = 0;
                $refundPrice = 0;
                foreach ($orders as $item) {
                    $totalPrice = $totalPrice + $item->pay_price;
                }
                break;     
            case 'kj':
                $query = Order::find()->andWhere([
                    'is_delete' => Order::IS_DELETE_FALSE,
                    'is_cancel' => Order::IS_CANCEL_FALSE,
                    'store_id' => $this->getCurrentStoreId(),
                    'type' => 2,
                ])->andWhere(['or', ['is_pay' => Order::IS_PAY_TRUE], ['pay_type' => Order::PAY_TYPE_COD]]);

                if ($startTime !== null) {
                    $query->andWhere(['>=', 'addtime', $startTime]);
                }
                if ($endTime !== null) {
                    $query->andWhere(['<=', 'addtime', $endTime]);
                }
                $orders = $query->with(['refund' => function ($query) {
                    $query->andWhere(['type' => OrderRefund::TYPE_REFUND, 'status' => OrderRefund::STATUS_REFUND_AGREE]);
                }])->all();

                $totalPrice = 0;
                $refundPrice = 0;
                foreach ($orders as $item) {
                    $totalPrice = $totalPrice + $item->pay_price;
                    foreach ($item->refund as $i) {
                        $refundPrice = $refundPrice + $i->refund_price;
                    }
                }

                break;
            case 'jf':
                $query = IntegralOrder::find()->where([
                    'is_delete' => Order::IS_DELETE_FALSE,
                    'is_cancel' => Order::IS_CANCEL_FALSE,
                    'store_id' => $this->getCurrentStoreId(),
                    'apply_delete'=> 0
                ])->andWhere(['or', ['is_pay' => Order::IS_PAY_TRUE], ['pay_type' => Order::PAY_TYPE_COD]]);

                if ($startTime !== null) {
                    $query->andWhere(['>=', 'addtime', $startTime]);
                }
                if ($endTime !== null) {
                    $query->andWhere(['<=', 'addtime', $endTime]);
                }

                $orders = $query->all();

                $totalPrice = 0;
                $refundPrice = 0;
                foreach ($orders as $item) {
                    $totalPrice = $totalPrice + $item->pay_price;
                }
                break;
            case 'mch':
                $where = ['and', ['store_id' => $this->getCurrentStoreId(), 'is_cancel' => Order::IS_CANCEL_FALSE, 'is_delete' => Model::IS_DELETE_FALSE],['<>', 'mch_id', 0]];
                $query = Order::find()->andWhere($where)->andWhere(['or', ['is_pay' => Order::IS_PAY_TRUE], ['pay_type' => Order::PAY_TYPE_COD]]);

                if ($startTime !== null) {
                    $query->andWhere(['>=', 'addtime', $startTime]);
                }
                if ($endTime !== null) {
                    $query->andWhere(['<=', 'addtime', $endTime]);
                }
                $orders = $query->with(['refund' => function ($query) {
                    $query->andWhere(['type' => OrderRefund::TYPE_REFUND, 'status' => OrderRefund::STATUS_REFUND_AGREE]);
                }])->all();

                $totalPrice = 0;
                $refundPrice = 0;
                foreach ($orders as $item) {
                    $totalPrice = $totalPrice + $item->pay_price;
                    foreach ($item->refund as $i) {
                        $refundPrice = $refundPrice + $i->refund_price;
                    }
                }
                break;
            default:
               return;
        }
        return doubleval((string)$totalPrice - $refundPrice);
    }


    /**
     * 获取订单总数
     * @param null $startTime
     * @param null $endTime
     * @param int $mchId
     * @return int
     */
    public function getOrderCount($table, $startTime = null, $endTime = null)
    {
        switch ($table){
            case 'pt':
                $query = PtOrder::find()->andWhere([
                    'is_delete' => Order::IS_DELETE_FALSE,
                    'is_cancel' => Order::IS_CANCEL_FALSE,
                    'store_id' => $this->getCurrentStoreId(),
                ])->andWhere(['or', ['is_pay' => Order::IS_PAY_TRUE], ['pay_type' => Order::PAY_TYPE_COD]]); 
                break;
            case 'ms':
                $query = MsOrder::find()->andWhere([
                    'is_delete' => Order::IS_DELETE_FALSE,
                    'is_cancel' => Order::IS_CANCEL_FALSE,
                    'store_id' => $this->getCurrentStoreId(),
                ])->andWhere(['or', ['is_pay' => Order::IS_PAY_TRUE], ['pay_type' => Order::PAY_TYPE_COD]]); 
                break;
            case 'yy':
                $query = YyOrder::find()->andWhere([
                    'is_delete' => Order::IS_DELETE_FALSE,
                    'is_cancel' => Order::IS_CANCEL_FALSE,
                    'store_id' => $this->getCurrentStoreId(),
                ])->andWhere(['<>','is_refund',1])->andWhere(['or', ['is_pay' => Order::IS_PAY_TRUE], ['pay_type' => Order::PAY_TYPE_COD]]);
                break;     
            case 'kj':
                $query = Order::find()->andWhere([
                    'is_delete' => Order::IS_DELETE_FALSE,
                    'is_cancel' => Order::IS_CANCEL_FALSE,
                    'store_id' => $this->getCurrentStoreId(),
                    'type' => 2,
                ])->andWhere(['or', ['is_pay' => Order::IS_PAY_TRUE], ['pay_type' => Order::PAY_TYPE_COD]]); 

                break;
            case 'jf':
                $query = IntegralOrder::find()->andWhere([
                    'is_delete' => Order::IS_DELETE_FALSE,
                    'is_cancel' => Order::IS_CANCEL_FALSE,
                    'store_id' => $this->getCurrentStoreId(),
                    'apply_delete'=> 0
                ])->andWhere(['or', ['is_pay' => Order::IS_PAY_TRUE], ['pay_type' => Order::PAY_TYPE_COD]]);
                break;
            case 'mch':
                $where = ['and', ['store_id' => $this->getCurrentStoreId(), 'is_cancel' => Order::IS_CANCEL_FALSE, 'is_delete' => Model::IS_DELETE_FALSE],['<>', 'mch_id', 0]];
                $query = Order::find()->andWhere($where)->andWhere(['or', ['is_pay' => Order::IS_PAY_TRUE], ['pay_type' => Order::PAY_TYPE_COD]]); 

            default:
               return;
        }

        if ($startTime !== null) {
            $query->andWhere(['>=', 'addtime', $startTime]);
        }
        if ($endTime !== null) {
            $query->andWhere(['<=', 'addtime', $endTime]);
        }
        
        
        if($table=='kj' || $table=='pt' || $table=='ms' || $table=='mch'){
            $order = $query->with('orderDetail', 'refund')->all();
            $orderCount = 0;
            foreach ($order as $item) {
                $orderDetailCount = count($item->orderDetail);

                $orderRefundCount = 0;
                if (!empty($item->refund)) {
                    foreach ($item->refund as $i) {
                        if ($i->type == OrderRefund::TYPE_REFUND && $i->status == OrderRefund::STATUS_REFUND_AGREE) {
                            $orderRefundCount++;
                        }
                    }
                }
                $orderCount++;

                if ($orderDetailCount == $orderRefundCount) {
                    $orderCount--;
                }
            }
        } else {
            $orderCount = $query->count();
        }

        return $orderCount;
    }

    /**
     * 获取售后中订单数
     * @param $table
     * @return int
     */
    public function getOrderRefundingCount($table)
    {
        switch ($table){
            case 'pt':
                $count = PtOrderRefund::find()->alias('or')
                    ->leftJoin(['o' => PtOrder::tableName()], 'o.id=or.order_id')->where([
                        'or.store_id' => $this->getCurrentStoreId(),
                        'or.is_delete' => Model::IS_DELETE_FALSE,
                        'or.status' => OrderRefund::STATUS_IN,
                    ])->count();
            break;
            case 'ms':
                $count = MsOrderRefund::find()->alias('or')
                    ->leftJoin(['o' => MsOrder::tableName()], 'o.id=or.order_id')->where([
                        'or.store_id' => $this->getCurrentStoreId(),
                        'or.is_delete' => Model::IS_DELETE_FALSE,
                        'or.status' => OrderRefund::STATUS_IN,
                    ])->count();
            break;
            case 'yy':
                $count = YyOrder::find()->where([
                        'store_id' => $this->getCurrentStoreId(),
                        'is_delete' => Model::IS_DELETE_FALSE,
                        'apply_delete' => 1,
                        'is_refund'=> 0,
                    ])->count();
            break;     
            case 'kj':
                $count = OrderRefund::find()->alias('or')
                    ->leftJoin(['o' => Order::tableName()], 'o.id=or.order_id')->where([
                        'or.store_id' => $this->getCurrentStoreId(),
                        'or.is_delete' => Model::IS_DELETE_FALSE,
                        'or.status' => OrderRefund::STATUS_IN,
                        'o.type' => 2,
                ])->count();
            break;
            case 'jf':
                $count = IntegralOrder::find()->where([
                    'store_id' => $this->getCurrentStoreId(),
                    'is_delete' => Model::IS_DELETE_FALSE,
                    'apply_delete' => 1,
                ])->count();
            break;
            case 'mch':
                $where = ['and', ['or.store_id' => $this->getCurrentStoreId(), 'or.status' => OrderRefund::STATUS_IN, 'or.is_delete' => Model::IS_DELETE_FALSE],['<>', 'o.mch_id', 0]];
                $count = OrderRefund::find()->alias('or')
                    ->leftJoin(['o' => Order::tableName()], 'o.id=or.order_id')->where($where)->count();
                break;
            default:
            break;
        }

        return $count ? intval($count) : 0;
    }


    /**
     * 获取待发货的订单数
     * @param int $mchId
     * @return int
     */
    public function getOrderNoSendCount($table)
    {
        switch ($table){
            case 'pt':
                $count = PtOrder::find()->where([
                    'store_id' => $this->getCurrentStoreId(),
                    'is_delete' => Model::IS_DELETE_FALSE,
                    'is_send' => Order::IS_SEND_FALSE,
                    'is_returnd' => 0
                ])->andWhere(['or', ['is_pay' => Order::IS_PAY_TRUE], ['pay_type' => Order::PAY_TYPE_COD]])->count();
                break;
            case 'ms':
                $count = MsOrder::find()->where([
                    'store_id' => $this->getCurrentStoreId(),
                    'is_delete' => Model::IS_DELETE_FALSE,
                    'is_send' => Order::IS_SEND_FALSE,
                ])->andWhere(['or', ['is_pay' => Order::IS_PAY_TRUE], ['pay_type' => Order::PAY_TYPE_COD]])->count();
                break;
            case 'kj':
                $count = Order::find()->where([
                    'store_id' => $this->getCurrentStoreId(),
                    'is_delete' => Model::IS_DELETE_FALSE,
                    'is_send' => Order::IS_SEND_FALSE,
                    'type' => 2,
                ])->andWhere(['or', ['is_pay' => Order::IS_PAY_TRUE], ['pay_type' => Order::PAY_TYPE_COD]])->count();

                break;
            case 'yy':
                $count = YyOrder::find()->where([
                    'store_id' => $this->getCurrentStoreId(),
                    'is_delete' => Model::IS_DELETE_FALSE,
                ])->andWhere(['or', ['is_pay' => Order::IS_PAY_TRUE], ['pay_type' => Order::PAY_TYPE_COD]])->count();

                break;     
            case 'jf':
                $count = IntegralOrder::find()->where([
                    'store_id' => $this->getCurrentStoreId(),
                    'is_delete' => Model::IS_DELETE_FALSE,
                    'is_send' => Order::IS_SEND_FALSE,
                ])->andWhere(['or', ['is_pay' => Order::IS_PAY_TRUE], ['pay_type' => Order::PAY_TYPE_COD]])->count();
                break;
            case 'mch':
                $where = ['and', ['store_id' => $this->getCurrentStoreId(), 'is_send' => Order::IS_SEND_FALSE, 'is_delete' => Model::IS_DELETE_FALSE],['<>', 'mch_id', 0]];
                $count = Order::find()->where($where)->andWhere(['or', ['is_pay' => Order::IS_PAY_TRUE], ['pay_type' => Order::PAY_TYPE_COD]])->count();
            default:
               break;
        };
        return $count ? intval($count) : 0;
    }

    /**
     * 获取商品销量排行
     * @param null $startTime
     * @param null $endTime
     * @param int $mchId
     * @param int $limit
     * @return mixed
     */
    public function getGoodsSaleTopList($table,$startTime = null, $endTime = null, $limit = 10)
    {
        switch ($table){
            case 'pt':
                $query = PtOrderDetail::find()->alias('od')
                    ->leftJoin(['o' => PtOrder::tableName()], 'od.order_id=o.id')
                    ->leftJoin(['g' => PtGoods::tableName()], 'od.goods_id=g.id')
                    ->where([
                        'g.store_id' => $this->getCurrentStoreId(),
                        'g.is_delete' => Model::IS_DELETE_FALSE,
                        'o.is_delete' => Model::IS_DELETE_FALSE,
                        'od.is_delete' => Model::IS_DELETE_FALSE,
                    ])->andWhere(['or', ['o.is_pay' => Order::IS_PAY_TRUE], ['o.pay_type' => Order::PAY_TYPE_COD]]);
                break;
            case 'ms':
                $query = MsOrder::find()->alias('od')
                    ->leftJoin(['g' => MsGoods::tableName()], 'od.goods_id=g.id')
                    ->where([
                        'g.store_id' => $this->getCurrentStoreId(),
                        'g.is_delete' => Model::IS_DELETE_FALSE,
                        'od.is_delete' => Model::IS_DELETE_FALSE,
                    ])->andWhere(['or', ['od.is_pay' => Order::IS_PAY_TRUE], ['od.pay_type' => Order::PAY_TYPE_COD]]);
       
                if ($startTime !== null) {
                    $query->andWhere(['>=', 'od.addtime', $startTime]);
                }
                if ($endTime !== null) {
                    $query->andWhere(['<=', 'od.addtime', $endTime]);
                }

                return $query->select('g.name,count("od.goods_id") AS num')
                    ->groupBy('od.goods_id')->orderBy('num DESC')
                    ->limit($limit)->asArray()->all();

                break;
            case 'kj':
                $query = OrderDetail::find()->alias('od')
                    ->leftJoin(['o' => Order::tableName()], 'od.order_id=o.id')
                    ->leftJoin(['g' => Goods::tableName()], 'od.goods_id=g.id')
                    ->where([
                        'g.store_id' => $this->getCurrentStoreId(),
                        'g.is_delete' => Model::IS_DELETE_FALSE,
                        'o.is_delete' => Model::IS_DELETE_FALSE,
                        'od.is_delete' => Model::IS_DELETE_FALSE,
                        'o.type' => 2,
                    ])->andWhere(['or', ['o.is_pay' => Order::IS_PAY_TRUE], ['o.pay_type' => Order::PAY_TYPE_COD]]);

                break;
            case 'yy':
                $query = YyOrder::find()->alias('od')
                    ->leftJoin(['g' => YyGoods::tableName()], 'od.goods_id=g.id')
                    ->where([
                        'g.store_id' => $this->getCurrentStoreId(),
                        'g.is_delete' => Model::IS_DELETE_FALSE,
                        'od.is_delete' => Model::IS_DELETE_FALSE,
                    ])->andWhere(['or', ['od.is_pay' => Order::IS_PAY_TRUE], ['od.pay_type' => Order::PAY_TYPE_COD]]);

                if ($startTime !== null) {
                    $query->andWhere(['>=', 'od.addtime', $startTime]);
                }
                if ($endTime !== null) {
                    $query->andWhere(['<=', 'od.addtime', $endTime]);
                }
                return $query->select('g.name,count("od.goods_id") AS num')
                    ->groupBy('od.goods_id')->orderBy('num DESC')
                    ->limit($limit)->asArray()->all();
                break;     
            case 'jf':
                $query = IntegralOrderDetail::find()->alias('od')
                    ->leftJoin(['o' => IntegralOrder::tableName()], 'od.order_id=o.id')
                    ->leftJoin(['g' => IntegralGoods::tableName()], 'od.goods_id=g.id')
                    ->where([
                        'g.store_id' => $this->getCurrentStoreId(),
                        'g.is_delete' => Model::IS_DELETE_FALSE,
                        'o.is_delete' => Model::IS_DELETE_FALSE,
                        'od.is_delete' => Model::IS_DELETE_FALSE,
                    ])->andWhere(['or', ['o.is_pay' => Order::IS_PAY_TRUE], ['o.pay_type' => Order::PAY_TYPE_COD]]);
                        break;
                break;
            case 'mch':
                $where = ['and', ['g.store_id' => $this->getCurrentStoreId(), 'g.is_delete' => Model::IS_DELETE_FALSE, 'o.is_delete' => Model::IS_DELETE_FALSE, 'od.is_delete' => Model::IS_DELETE_FALSE],['<>', 'g.mch_id', 0]];
                $query = OrderDetail::find()->alias('od')
                    ->leftJoin(['o' => Order::tableName()], 'od.order_id=o.id')
                    ->leftJoin(['g' => Goods::tableName()], 'od.goods_id=g.id')
                    ->where($where)->andWhere(['or', ['o.is_pay' => Order::IS_PAY_TRUE], ['o.pay_type' => Order::PAY_TYPE_COD]]);
            default:
               break;
        };

        if ($startTime !== null) {
            $query->andWhere(['>=', 'o.addtime', $startTime]);
        }
        if ($endTime !== null) {
            $query->andWhere(['<=', 'o.addtime', $endTime]);
        }
        return $query->select('g.name,SUM(od.num) AS num')
            ->groupBy('od.goods_id')->orderBy('num DESC')
            ->limit($limit)->asArray()->all();
    }
}
