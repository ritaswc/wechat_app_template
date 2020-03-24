<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/4
 * Time: 18:17
 */

namespace app\modules\user\models;

use app\models\common\admin\order\CommonOrderStatistics;
use app\models\common\admin\store\CommonStore;
use app\models\Goods;
use app\models\Order;
use app\models\OrderDetail;

class MchDataForm extends UserModel
{
    public $store_id;
    public $mch_id;


    public function search()
    {
        //今日
        $todayStartTime = strtotime(date('Y-m-d 00:00:00'));
        $todayEndTime = strtotime(date('Y-m-d 23:59:59'));
        //昨日
        $yesterdayStartTime = strtotime(date('Y-m-d 00:00:00') . ' -1 day');
        $yesterdayEndTime = strtotime(date('Y-m-d 23:59:59') . ' -1 day');
        //最近7天
        $lastSevenStartTime = strtotime(date('Y-m-d 00:00:00') . ' -6 day');
        $lastSevenEndTime = strtotime(date('Y-m-d 23:59:59'));
        //最近30天
        $lastThirtyStartTime = strtotime(date('Y-m-d 00:00:00') . ' -29 day');
        $lastThirtyEndTime = strtotime(date('Y-m-d 23:59:59'));

        $data = [
            'panel_1' => $this->getMchInfo(),
            'panel_2' => [
                'goods_zero_count' => $this->getCountZeroGoodsNum(),
                'order_no_send_count' => $this->getOrderNoSendCount(),
                'order_refunding_count' => $this->getOrderRefundingCount(),
            ],
            'panel_3' => [
                'date' => [
                    $this->getDayData($todayStartTime, $todayEndTime),
                    $this->getDayData($yesterdayStartTime, $yesterdayEndTime),
                    $this->getDayData($lastSevenStartTime, $lastSevenEndTime),
                    $this->getDayData($lastThirtyStartTime, $lastThirtyEndTime),
                ],
                'month' => $this->getMonthData()
            ],
            'panel_4' => null,
            'panel_5' => [
                $this->getGoodsSaleTopList($todayStartTime, $todayEndTime),
                $this->getGoodsSaleTopList($yesterdayStartTime, $yesterdayEndTime),
                $this->getGoodsSaleTopList($lastSevenStartTime, $lastSevenEndTime),
                $this->getGoodsSaleTopList($lastThirtyStartTime, $lastThirtyEndTime),
            ],
        ];

        return [
            'code' => 0,
            'data' => $data
        ];
    }

    public function getMchInfo()
    {
        $common = new CommonStore();
        $storeInfo = $common->storeInfo($this->mch_id);

        return $storeInfo;
    }

    //获取已售罄商品
    public function getCountZeroGoodsNum()
    {
        $cache_key = 'mch_zero_goods_nym_' . $this->store_id;
        $count = \Yii::$app->cache->get($cache_key);
        if ($count !== false) {
            return $count;
        }
        /** @var Goods[] $goods_list */
        $goods_list = Goods::find()->where([
            'is_delete' => 0,
            'store_id' => $this->store_id,
            'mch_id' => $this->mch_id
        ])->select('id,attr')->all();
        $count = 0;
        foreach ($goods_list as $goods) {
            if ($goods->getNum() == 0) {
                $count++;
            }
        }
        \Yii::$app->cache->set($cache_key, $count, 60);
        return $count;
    }


    /**
     * 获取待发货订单数
     */
    public function getOrderNoSendCount()
    {
        $common = new CommonOrderStatistics();
        $noSendCount = $common->getOrderNoSendCount($this->mch_id);

        return $noSendCount;
    }

    /**
     * 获取售后中订单数
     */
    public function getOrderRefundingCount()
    {
        $common = new CommonOrderStatistics();
        $refundCount = $common->getOrderRefundingCount($this->mch_id);

        return $refundCount;
    }

    /**
     * 获取商品销量排行
     * @param null $startTime
     * @param null $endTime
     * @return mixed
     */
    public function getGoodsSaleTopList($startTime = null, $endTime = null)
    {
        $common = new CommonOrderStatistics();
        $goodsSaleTop = $common->getGoodsSaleTopList($startTime, $endTime, $this->mch_id, 5);

        return $goodsSaleTop;
    }

    public function getDayData($start_time = null, $end_time = null)
    {
        $list = [];
        $list['order_goods_count'] = $this->getOrderGoodsCount($start_time, $end_time);
        $list['order_price_count'] = $this->getOrderPriceCount($start_time, $end_time);
        $list['order_count'] = $this->getOrderCount($start_time, $end_time);
        $list['order_price_average'] = $this->getOrderPriceAverage($start_time, $end_time);
        return $list;
    }

    /**
     * 获取订单商品总数
     * @param null $startTime
     * @param null $endTime
     * @return int
     */
    public function getOrderGoodsCount($startTime = null, $endTime = null)
    {
        $common = new CommonOrderStatistics();
        $orderGoodsCount = $common->getOrderGoodsCount($startTime, $endTime, $this->mch_id);
        return $orderGoodsCount;
    }

    /**
     * 获取订单金额总数（实际付款）
     * @param null $startTime
     * @param null $endTime
     * @return string
     */
    public function getOrderPriceCount($startTime = null, $endTime = null)
    {
        $common = new CommonOrderStatistics();
        $orderPriceCount = $common->getOrderPriceCount($startTime, $endTime, $this->mch_id);
        return $orderPriceCount;
    }

    /**
     * 获取订单总数
     * @param null $startTime
     * @param null $endTime
     * @return int
     */
    public function getOrderCount($startTime = null, $endTime = null)
    {
        $common = new CommonOrderStatistics();
        $orderCount = $common->getOrderCount($startTime, $endTime, $this->mch_id);

        return $orderCount;
    }

    /**
     * 获取订单平均消费金额（实际付款）
     * @param null $startTime
     * @param null $endTime
     * @return float|int
     */
    public function getOrderPriceAverage($startTime = null, $endTime = null)
    {
        $common = new CommonOrderStatistics();
        $orderCount = $common->getOrderCount($startTime, $endTime, $this->mch_id);
        if ($orderCount == 0) {
            return 0;
        }
        $priceCount = $common->getOrderPriceCount($startTime, $endTime, $this->mch_id);

        $price = $priceCount / $orderCount;
        $price = doubleval(sprintf('%.2f', $price));

        return $price;
    }

    /**
     * 获取每月数据
     */
    public function getMonthData()
    {
        $list = [];
        for ($i = 1; $i <= 12; $i++) {
            $new_list = [];
            $new_list['order_goods_count'] = $this->getMonthOrderGoodsCount($i);
            $new_list['order_price_count'] = $this->getMonthOrderPriceCount($i);
            $new_list['order_count'] = $this->getMonthOrderCount($i);
            if ($new_list['order_count'] == 0) {
                $new_list['order_price_average'] = 0;
            } else {
                $price = $new_list['order_price_count'] / $new_list['order_count'];
                $price = doubleval(sprintf('%.2f', $price));
                $new_list['order_price_average'] = $price;
            }
            if (in_array($i, [1, 3, 5, 7, 8, 10, 12])) {
                $count_1 = $new_list['order_goods_count'] / 31;
                $count_2 = $new_list['order_price_count'] / 31;
            } elseif (in_array($i, [4, 6, 9, 11])) {
                $count_1 = $new_list['order_goods_count'] / 30;
                $count_2 = $new_list['order_price_count'] / 30;
            } else {
                if (date('Y') % 4 == 0) {
                    $count_1 = $new_list['order_goods_count'] / 29;
                    $count_2 = $new_list['order_price_count'] / 29;
                } else {
                    $count_1 = $new_list['order_goods_count'] / 28;
                    $count_2 = $new_list['order_price_count'] / 28;
                }
            }
            $new_list['day_order_goods_count'] = $count_1 ? intval($count_1) : 0;
            $new_list['day_order_price_count'] = $count_2 ? doubleval(sprintf('%.2f', $count_2)) : 0;
            if ($i == 1) {
                $new_list['order_price_compare'] = $new_list['order_price_count'];
            } else {
                $last_list = $list[$i - 2];
                $compare_price = $new_list['order_price_count'] - $last_list['order_price_count'];
                $new_list['order_price_compare'] = isset($compare_price) ? doubleval(sprintf('%.2f', $compare_price)) : 0;
            }
            $list[] = $new_list;
        }
        return $list;
    }

    /**
     * 获取订单商品总数
     */
    public function getMonthOrderGoodsCount($i)
    {
        $query = OrderDetail::find()->alias('od')
            ->leftJoin(['o' => Order::tableName()], 'od.order_id=o.id')
            ->where([
                'o.is_delete' => 0,
                'od.is_delete' => 0,
                'o.store_id' => $this->store_id,
                'o.mch_id' => $this->mch_id,
                'MONTH(FROM_UNIXTIME(o.addtime))' => $i
            ])->andWhere(['or', ['o.is_pay' => 1], ['o.pay_type' => 2]]);
        $count = $query->select('SUM(od.num) AS num')->asArray()->one();
        return $count['num'] ? intval($count['num']) : 0;
    }

    /**
     * 获取订单金额总数（实际付款）
     */
    public function getMonthOrderPriceCount($i)
    {
        $query = Order::find()->alias('o')
            ->where([
                'o.is_delete' => 0,
                'o.store_id' => $this->store_id,
                'o.mch_id' => $this->mch_id,
                'o.is_cancel' => 0,
                'MONTH(FROM_UNIXTIME(o.addtime))' => $i
            ])->andWhere(['or', ['o.is_pay' => 1], ['o.pay_type' => 2]]);
        $count = $query->select('SUM(o.pay_price) AS total_price')->asArray()->one();
        return $count['total_price'] ? doubleval($count['total_price']) : 0;
    }

    /**
     * 获取订单平均消费金额（实际付款）
     */
    public function getMonthOrderCount($i)
    {
        $query = Order::find()->alias('o')
            ->where([
                'o.is_delete' => 0,
                'o.store_id' => $this->store_id,
                'o.mch_id' => $this->mch_id,
                'o.is_cancel' => 0,
                'MONTH(FROM_UNIXTIME(o.addtime))' => $i
            ])->andWhere(['or', ['o.is_pay' => 1], ['o.pay_type' => 2]]);
        $count = $query->count();
        $count = $count ? intval($count) : 0;
        return $count;
    }
}
