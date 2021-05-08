<?php

namespace app\modules\mch\models;

use app\models\common\admin\store\CommonStore;
use app\models\common\admin\order\CommonOrderPlugStats;
use app\models\Goods;
use app\models\Model;
use app\models\Order;
use app\models\User;

use app\models\PtOrder;
use app\models\MsOrder;
use app\models\BargainOrder;
use app\models\YyOrder;
use app\models\IntegralOrder;

use app\models\PtGoods;
use app\models\YyGoods;
use app\models\MsGoods;
use app\models\IntegralGoods;


class StoreStatsForm extends MchModel
{
    public $store_id;
    public $is_offline;
    public $user_id;
    public $clerk_id;
    public $parent_id;
    public $shop_id;
    public $sign;
    public $type;

    public $name;

    public function search()
    {
        $date = $this->getDate();

        $name = $this->name;

        if ($this->type == 'order') {
            return [
                'code' => 0,
                'data' => $this->getOrderStatistics($name),
            ];
        }

        if ($this->type == 'goods') {
            return [
                'code' => 0,
                'data' => $this->getGoodsStatistics($name),
            ];
        }


        $data = [
            'panel_1' => $this->getStoreInfoPlug($name),
            'panel_2' => [
                'goods_zero_count' => $this->getCountZeroGoodsNumPlug($name), 
                'order_no_send_count' => $this->getOrderNoSendCountPlug($name),
                'order_refunding_count' => $this->getOrderRefundingCountPlug($name),
            ],
            'panel_3' => [
                'data_1' => [
                    'order_goods_count' => $this->getOrderGoodsCountPlug($name, $date['startTime'], $date['endTime']),
                    'order_price_count' => $this->getOrderPriceCountPlug($name, $date['startTime'], $date['endTime']),
                    'order_price_average' => $this->getOrderPriceAveragePlug($name, $date['startTime'], $date['endTime']),
                ]
            ],
            'panel_4' => [
                'order_goods_data' => $this->getDaysOrderGoodsDataPlug($name,7),
                'order_goods_price_data' => $this->getDaysOrderGoodsPriceDataPlug($name,7),
            ],
            'panel_5' => [
                'data_1' => $this->getGoodsSaleTopListPlug($name, $date['startTime'], $date['endTime'], 5),
            ],
            'panel_6' => $this->getUserTopListPlug($name,10),
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

    public function getStoreInfoPlug($name)
    {
        $common = new CommonStore();
        $storeInfo = $common->storeInfoPlug($name);

        return $storeInfo;
    }

    /**
     * 获取售罄商品数量
     */
    public function getCountZeroGoodsNumPlug($name)
    {
        $cache_key = 'zero_'.$name.'goods_nym_' . $this->getCurrentStoreId();

        $count = \Yii::$app->cache->get($cache_key);
        if ($count !== false) {
            return $count;
        }
        switch ($name){
            case 'pt':
                $goods = PtGoods::find();
                break;
            case 'ms':
                $goods = MsGoods::find();
                break;
            case 'kj':
                break;
            case 'yy':
                $goods = YyGoods::find();
                break;     
            case 'jf':
                $goods = IntegralGoods::find();
                break;
            case 'mch':
                break;
            default:
               return;
        }

        /** @var Goods[] $goods_list */
        if($name=='kj'){
            $goods_list = Goods::find()->where([
                'is_delete' => Model::IS_DELETE_FALSE,
                'store_id' => $this->getCurrentStoreId(),
                'type' => 2
            ])->select('id,attr')->all();
        } else if ($name == 'mch') {
            $goods_list = Goods::find()->where(['and', ['store_id' => $this->getCurrentStoreId(), 'is_delete' => Model::IS_DELETE_FALSE],['<>', 'mch_id', 0]
            ])->select('id,attr')->all();
        } else {
            $goods_list = $goods->where([
                'is_delete' => Model::IS_DELETE_FALSE,
                'store_id' => $this->getCurrentStoreId(),
            ])->select('id,attr')->all();

        };

        $count = 0;
        if($name=='pt'){
            foreach ($goods_list as $goods) {
                if ($goods->getDNum() == 0) {
                    $count++;
                }
            }                
        }else{
            foreach ($goods_list as $goods) {
                if ($goods->getNum() == 0) {
                    $count++;
                }
            }      
        }

        \Yii::$app->cache->set($cache_key, $count, 60);
        return $count;
    }
    
    /**
     * 获取待发货订单数 
     */
    public function getOrderNoSendCountPlug($name)
    {
        $common = new CommonOrderPlugStats(); 
        $noSendCount = $common->getOrderNoSendCount($name);

        return $noSendCount;
    }

    /**
     * 获取售后中订单数
     */
    public function getOrderRefundingCountPlug($name){
        $common = new CommonOrderPlugStats();
        $refundCount = $common->getOrderRefundingCount($name);

        return $refundCount;
    }


    /**
     * 获取订单商品总数
     * @param null $startTime
     * @param null $endTime
     * @return int
     */
    public function getOrderGoodsCountPlug($name,$startTime = null, $endTime = null)
    {
        $common = new CommonOrderPlugStats();
        return $common->getOrderGoodsCount($name,$startTime, $endTime);
    }

    /**
     * 获取订单金额总数（实际付款）
     * @param null $startTime
     * @param null $endTime
     * @return string
     */
    public function getOrderPriceCountPlug($name,$startTime = null, $endTime = null)
    {
        $common = new CommonOrderPlugStats();
        $orderPriceCount = $common->getOrderPriceCount($name,$startTime, $endTime);

        return number_format($orderPriceCount, 2);
    }

    /**
     * 获取订单平均消费金额（实际付款）
     * @param null $startTime
     * @param null $endTime
     * @return float|int
     */
    public function getOrderPriceAveragePlug($name,$startTime = null, $endTime = null)
    {
        $common = new CommonOrderPlugStats();
        $orderCount = $common->getOrderCount($name, $startTime, $endTime);


        if ($orderCount == 0) {
            return number_format($orderCount, 2);
        }

        $priceCount = $common->getOrderPriceCount($name,$startTime, $endTime);
        $price = number_format($priceCount / $orderCount, 2);

        return $price;
    }



    /**
     * 获取n日内每日销量
     */
    public function getDaysOrderGoodsDataPlug($name,$days = 7)
    {
        $list = [];
        $data = [];
        for ($i = 0; $i < $days; $i++) {
            $startTime = strtotime(date('Y-m-d 00:00:00') . ' -' . $i . ' days');
            $endTime = strtotime(date('Y-m-d 23:59:59') . ' -' . $i . ' days');
            $date = date('m-d', $startTime);
            $common = new CommonOrderPlugStats();
            $val = $common->getOrderGoodsCount($name,$startTime, $endTime);

            $list[] = [
                'date' => $date,
                'val' => $val,
                'start_time' => date('Y-m-d H:i:s', $startTime),
                'end_time' => date('Y-m-d H:i:s', $endTime),
            ];
            $data[] = $val;
        }

        return [
            'list' => array_reverse($list),
            'data' => array_reverse($data),
        ];
    }

    /**
     * 获取n日内每日成交额（已付款）
     */
    public function getDaysOrderGoodsPriceDataPlug($type,$days = 7)
    {
        $list = [];
        $data = [];
        for ($i = 0; $i < $days; $i++) {
            $startTime = strtotime(date('Y-m-d 00:00:00') . ' -' . $i . ' days');
            $endTime = strtotime(date('Y-m-d 23:59:59') . ' -' . $i . ' days');
            $date = date('m-d', $startTime);
            $common = new CommonOrderPlugStats();
            $orderPriceCount = $common->getOrderPriceCount($type,$startTime, $endTime);
            $list[] = [
                'date' => $date,
                'val' => $orderPriceCount,
                'start_time' => date('Y-m-d H:i:s', $startTime),
                'end_time' => date('Y-m-d H:i:s', $endTime),
            ];
            $data[] = $orderPriceCount;
        }

        return [
            'list' => array_reverse($list),
            'data' => array_reverse($data),
        ];
    }

    public function getOrderStatistics($name)
    {
        $date = $this->getDate();
        return [
            'panel_3' => [
                'data_1' => [
                    'order_goods_count' => $this->getOrderGoodsCountPlug($name,$date['startTime'], $date['endTime']),
                    'order_price_count' => $this->getOrderPriceCountPlug($name,$date['startTime'], $date['endTime']),
                    'order_price_average' => $this->getOrderPriceAveragePlug($name,$date['startTime'], $date['endTime']),
                ]
            ]
        ];
    }

    public function getGoodsStatistics($name)
    {
        $date = $this->getDate();
        return [
            'panel_5' => [
                'data_1' => $this->getGoodsSaleTopListPlug($name,$date['startTime'], $date['endTime'], 5),
            ]
        ];
    }



    /**
     * 获取商品销量排行
     * @param null $startTime
     * @param null $endTime
     * @param null $mchId
     * @param null $limit
     * @return mixed
     */
    public function getGoodsSaleTopListPlug($name,$startTime = null, $endTime = null, $limit)
    {
        $common = new CommonOrderPlugStats();
        $goodsSaleTop = $common->getGoodsSaleTopList($name, $startTime, $endTime, $limit);

        return $goodsSaleTop;
    }

    /**
     * 获取用户消费排行列表
     */
    public function getUserTopListPlug($name, $limit = 10)
    {
        switch ($name){
            case 'pt':
                $order = PtOrder::find();
                break;
            case 'ms':
                $order = MsOrder::find();
                break;
            case 'kj':
                break;
            case 'yy':
                $order = YyOrder::find();
                break;     
            case 'jf':
                $order = IntegralOrder::find();
                break;
            case 'mch':
                break;
            default:
               return;
        }

        if($name=='kj') {
            $list = Order::find()->alias('o')->leftJoin(['u' => User::tableName()], 'o.user_id=u.id')
            ->where([
                'o.store_id' => $this->getCurrentStoreId(),
                'o.is_pay' => Order::IS_PAY_TRUE,
                'o.is_delete' => Model::IS_DELETE_FALSE,
                'o.type' => 2,
            ])->groupBy('o.user_id')->limit($limit)->orderBy('money DESC')
            ->select('u.id,u.nickname,u.avatar_url AS avatar,SUM(o.pay_price) AS money')
            ->asArray()->all();   
        } else if($name=='mch') {
            $where = ['and', ['o.store_id' => $this->getCurrentStoreId(), 'o.is_pay' => Order::IS_PAY_TRUE, 'o.is_delete' => Model::IS_DELETE_FALSE],['<>', 'o.mch_id', 0]];
            $list = Order::find()->alias('o')->leftJoin(['u' => User::tableName()], 'o.user_id=u.id')
            ->where($where)->groupBy('o.user_id')->limit($limit)->orderBy('money DESC')
            ->select('u.id,u.nickname,u.avatar_url AS avatar,SUM(o.pay_price) AS money')
            ->asArray()->all();
        } else {
            $list = $order->alias('o')->leftJoin(['u' => User::tableName()], 'o.user_id=u.id')
                ->where([
                    'o.store_id' => $this->getCurrentStoreId(),
                    'o.is_pay' => Order::IS_PAY_TRUE,
                    'o.is_delete' => Model::IS_DELETE_FALSE,
                ])->groupBy('o.user_id')->limit($limit)->orderBy('money DESC')
                ->select('u.id,u.nickname,u.avatar_url AS avatar,SUM(o.pay_price) AS money')
                ->asArray()->all();
        }
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
