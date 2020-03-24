<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/27
 * Time: 14:14
 */

namespace app\modules\mch\models;

use app\models\common\admin\order\CommonOrderStatistics;
use app\models\common\admin\store\CommonStore;
use app\models\Goods;
use app\models\Model;
use app\models\Order;
use app\models\User;

class StoreDataForm extends MchModel
{
    public $store_id;
    public $is_offline;
    public $user_id;
    public $clerk_id;
    public $parent_id;
    public $shop_id;
    public $sign;
    public $type;

    public function search()
    {
        $date = $this->getDate();

        if ($this->type == 'order') {
            return [
                'code' => 0,
                'data' => $this->getOrderStatistics(),
            ];
        }

        if ($this->type == 'goods') {
            return [
                'code' => 0,
                'data' => $this->getGoodsStatistics(),
            ];
        }

        $data = [
            'panel_1' => $this->getStoreInfo(),
            'panel_2' => [
                'goods_zero_count' => $this->getCountZeroGoodsNum(),
                'order_no_send_count' => $this->getOrderNoSendCount(),
                'order_refunding_count' => $this->getOrderRefundingCount(),
            ],
            'panel_3' => [
                'data_1' => [ //todo 待优化这三项
                    'order_goods_count' => $this->getOrderGoodsCount($date['startTime'], $date['endTime']),
                    'order_price_count' => $this->getOrderPriceCount($date['startTime'], $date['endTime']),
                    'order_price_average' => $this->getOrderPriceAverage($date['startTime'], $date['endTime']),
                ]
            ],
            'panel_4' => [
                'order_goods_data' => $this->getDaysOrderGoodsData(7),
                'order_goods_price_data' => $this->getDaysOrderGoodsPriceData(7),
            ],
            'panel_5' => [
                'data_1' => $this->getGoodsSaleTopList($date['startTime'], $date['endTime'], 0, 5),
            ],
            'panel_6' => $this->getUserTopList(10),
        ];

        $data['panel_4']['date'] = [];
        foreach ($data['panel_4']['order_goods_data']['list'] as $item) {
            $data['panel_4']['date'][] = $item['date'];
        }
        return [
            'code' => 0,
            'data' => $data,
        ];
    }

    public function getStoreInfo()
    {
        $common = new CommonStore();
        $storeInfo = $common->storeInfo();

        return $storeInfo;
    }


    public function getOrderStatistics()
    {
        $date = $this->getDate();
        return [
            'panel_3' => [
                'data_1' => [
                    'order_goods_count' => $this->getOrderGoodsCount($date['startTime'], $date['endTime']),
                    'order_price_count' => $this->getOrderPriceCount($date['startTime'], $date['endTime']),
                    'order_price_average' => $this->getOrderPriceAverage($date['startTime'], $date['endTime']),
                ]
            ]
        ];
    }

    public function getGoodsStatistics()
    {
        $date = $this->getDate();
        return [
            'panel_5' => [
                'data_1' => $this->getGoodsSaleTopList($date['startTime'], $date['endTime'], 0, 5),
            ]
        ];
    }

    /**
     * 获取售罄商品数量
     */
    public function getCountZeroGoodsNum()
    {
        $cache_key = 'zero_goods_nym_' . $this->getCurrentStoreId();
        $count = \Yii::$app->cache->get($cache_key);
        if ($count !== false) {
            return $count;
        }
        /** @var Goods[] $goods_list */
        $goods_list = Goods::find()->where([
            'is_delete' => Model::IS_DELETE_FALSE,
            'store_id' => $this->getCurrentStoreId(),
            'type' => 0
        ])->select('id,attr')->all();
        $count = 0;
        foreach ($goods_list as $goods) {
            if ($goods->getNum() == 0) {
                $count++;
            }
        }
        \Yii::$app->cache->set($cache_key, $count, 266);
        return $count;
    }

    /**
     * 获取待发货订单数
     */
    public function getOrderNoSendCount()
    {
        //todo 待优化，建议使用联合索引
        $common = new CommonOrderStatistics();
        $noSendCount = $common->getOrderNoSendCount();
        return $noSendCount;
    }

    /**
     * 获取售后中订单数
     */
    public function getOrderRefundingCount()
    {
        $common = new CommonOrderStatistics();
        $refundCount = $common->getOrderRefundingCount();
        return $refundCount;
    }

    /**
     * 获取订单商品总数
     * @param null $startTime
     * @param null $endTime
     * @return int
     */
    public function getOrderGoodsCount($startTime = null, $endTime = null)
    {
        //todo 待优化，查询的字段过多
        $common = new CommonOrderStatistics();
        return $common->getOrderGoodsCount($startTime, $endTime);
    }

    /**
     * 获取订单金额总数（实际付款）
     * @param null $startTime
     * @param null $endTime
     * @return string
     */
    public function getOrderPriceCount($startTime = null, $endTime = null)
    {
        //todo 待优化，查询的字段过多
        $common = new CommonOrderStatistics();
        $orderPriceCount = $common->getOrderPriceCount($startTime, $endTime);

        return number_format($orderPriceCount, 2);
    }

    /**
     * 获取订单平均消费金额（实际付款）
     * @param null $startTime
     * @param null $endTime
     * @return float|int
     */
    public function getOrderPriceAverage($startTime = null, $endTime = null)
    {
        //todo 待优化，查询的字段过多
        $common = new CommonOrderStatistics();
        $orderCount = $common->getOrderCount($startTime, $endTime);

        if ($orderCount == 0) {
            return number_format($orderCount, 2);
        }

        $priceCount = $common->getOrderPriceCount($startTime, $endTime);
        $price = number_format($priceCount / $orderCount, 2);

        return $price;
    }

    /**
     * 获取n日内每日销量
     */
    public function getDaysOrderGoodsData($days = 7)
    {
        //todo 待优化，查询太慢，查询的字段过多，比较严重
        $cacheKey = md5('getDaysOrderGoodsData:days=' . $days . ';sessionId=' . \Yii::$app->session->getId());
        $res = \Yii::$app->cache->get($cacheKey);
        if ($res) {
            return $res;
        }
        $list = [];
        $data = [];
        for ($i = 0; $i < $days; $i++) {
            $startTime = strtotime(date('Y-m-d 00:00:00') . ' -' . $i . ' days');
            $endTime = strtotime(date('Y-m-d 23:59:59') . ' -' . $i . ' days');
            $date = date('m-d', $startTime);
            $common = new CommonOrderStatistics();
            $val = $common->getOrderGoodsCount($startTime, $endTime);

            $list[] = [
                'date' => $date,
                'val' => $val,
                'start_time' => date('Y-m-d H:i:s', $startTime),
                'end_time' => date('Y-m-d H:i:s', $endTime),
            ];
            $data[] = $val;
        }

        $res = [
            'list' => array_reverse($list),
            'data' => array_reverse($data),
        ];
        \Yii::$app->cache->set($cacheKey, $res, 300);
        return $res;
    }

    /**
     * 获取n日内每日成交额（已付款）
     */
    public function getDaysOrderGoodsPriceData($days = 7)
    {
        //todo 待优化，查询太慢，查询的字段过多
        $cacheKey = md5('getDaysOrderGoodsPriceData:days=' . $days . ';sessionId=' . \Yii::$app->session->getId());
        $res = \Yii::$app->cache->get($cacheKey);
        if ($res) {
            return $res;
        }
        $list = [];
        $data = [];
        for ($i = 0; $i < $days; $i++) {
            $startTime = strtotime(date('Y-m-d 00:00:00') . ' -' . $i . ' days');
            $endTime = strtotime(date('Y-m-d 23:59:59') . ' -' . $i . ' days');
            $date = date('m-d', $startTime);
            $common = new CommonOrderStatistics();
            $orderPriceCount = $common->getOrderPriceCount($startTime, $endTime);
            $list[] = [
                'date' => $date,
                'val' => $orderPriceCount,
                'start_time' => date('Y-m-d H:i:s', $startTime),
                'end_time' => date('Y-m-d H:i:s', $endTime),
            ];
            $data[] = $orderPriceCount;
        }

        $res = [
            'list' => array_reverse($list),
            'data' => array_reverse($data),
        ];
        \Yii::$app->cache->set($cacheKey, $res, 333);
        return $res;
    }


    /**
     * 获取商品销量排行
     * @param null $startTime
     * @param null $endTime
     * @param null $mchId
     * @param null $limit
     * @return mixed
     */
    public function getGoodsSaleTopList($startTime = null, $endTime = null, $mchId, $limit)
    {
        $common = new CommonOrderStatistics();
        $goodsSaleTop = $common->getGoodsSaleTopList($startTime, $endTime, $mchId, $limit);
        return $goodsSaleTop;
    }

    /**
     * 获取用户消费排行列表
     */
    public function getUserTopList($limit = 10)
    {
        $list = Order::find()->alias('o')->leftJoin(['u' => User::tableName()], 'o.user_id=u.id')
            ->where([
                'o.store_id' => $this->getCurrentStoreId(),
                'o.is_pay' => 1,
                'o.is_delete' => 0,
                'o.type' => 0
            ])->groupBy('o.user_id')->limit($limit)->orderBy('money DESC')
            ->select('u.id,u.nickname,u.avatar_url AS avatar,SUM(o.pay_price) AS money')
            ->asArray()->all();
        if (!$list) {
            return [];
        }
        foreach ($list as $i => $item) {
            $money = doubleval($item['money']);
            $list[$i]['money'] = number_format($money, 2, '.', '');
        }
        return $list;
    }

    public function getDate()
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

        switch ($this->sign) {
            case 1:
                $startTime = $todayStartTime;
                $endTime = $todayEndTime;
                break;
            case 2:
                $startTime = $yesterdayStartTime;
                $endTime = $yesterdayEndTime;
                break;
            case 3:
                $startTime = $lastSevenStartTime;
                $endTime = $lastSevenEndTime;
                break;
            case 4:
                $startTime = $lastThirtyStartTime;
                $endTime = $lastThirtyEndTime;
                break;
            default:
                $startTime = $todayStartTime;
                $endTime = $todayEndTime;
                break;
        }

        return [
            'startTime' => $startTime,
            'endTime' => $endTime,
        ];
    }
}
