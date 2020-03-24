<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/12/6
 * Time: 15:46
 */

namespace app\behaviors;

use app\utils\PinterOrder;
use app\models\PtGoods;
use app\models\PtNoticeSender;
use app\models\PtOrder;
use app\models\PtOrderDetail;
use app\models\Store;
use app\models\User;
use app\models\UserAccountLog;
use app\utils\Refund;
use luweiss\wechat\Wechat;
use yii\base\ActionEvent;
use yii\web\Controller;
use app\models\PtGoodsDetail;
use app\modules\mch\models\MchModel;

class PintuanBehavior extends BaseBehavior
{
    protected $only_routes = [
        'mch/group/*',
        'api/group/*',
        // 'mch/store/index',
    ];

    public $store_id;

    /**
     * @param ActionEvent $event
     * @return bool
     */
    public function beforeAction($event)
    {
        \Yii::warning('----PINTUAN BEHAVIOR----');
        if (!empty($event->action->controller->store) && !empty($event->action->controller->store->id)) {
            $this->store_id = $event->action->controller->store->id;
        }
        try {
            $this->checkOrderTimeout($event); //处理未在规定时间内成团的订单
            $this->checkOrderTimeoutRefund($event); //处理未在规定时间内成团的订单
            $this->checkNoPayOrderTimeout($event); //处理未在规定时间内付款的订单
            $this->checkGoodsTimeout($event); //处理超时的拼团商品自动下架
            $this->checkOrderConfirmTimeout($event); //处理超时自动确认收货的订单
            $this->checkPtOrderNumTimeout($event); //处理拼团人数达到总成团数后拼团成功
        } catch (\Exception $e) {
        }
    }

    /**
     * 处理未在规定时间内成团的订单
     * @param ActionEvent $event
     * @return bool
     */
    private function checkOrderTimeout($event)
    {
        $order_max = 100; //每次最多处理的团购个数，防止运行太久
        $cache_key = 'pt_order_timeout_checker';
        if (\Yii::$app->cache->get($cache_key)) {
            return true;
        }

        \Yii::$app->cache->set($cache_key, true, 10);

        /** @var Wechat $wechat */
        $wechat = isset($event->action->controller->wechat) ? $event->action->controller->wechat : null;
        if (!$wechat) {
            \Yii::$app->cache->set($cache_key, false);
            return true;
        }
        $parent_order_list_query = PtOrder::find()->where([
            'AND',
            [
                'is_group' => 1,
                'parent_id' => 0,
                'status' => 2,
            ],
            ['IS NOT', 'limit_time', null],
            ['<=', 'limit_time', time()],
        ])->andWhere([
            'OR',
            ['is_pay' => 1],
            ['pay_type' => 2],
        ])->limit($order_max);
        if ($this->store_id) {
            $parent_order_list_query->andWhere(['store_id' => $this->store_id]);
        }

        /** @var PtOrder[] $parent_order_list */
        $parent_order_list = $parent_order_list_query->all();

        foreach ($parent_order_list as $parent_order) {
            $sub_order_list = PtOrder::find()->where([
                'AND',
                [
                    'is_group' => 1,
                    'parent_id' => $parent_order->id,
                    'status' => 2,
                ],
            ])->andWhere([
                'OR',
                ['is_pay' => 1],
                ['pay_type' => 2],
            ])->all();
            /** @var PtOrder[] $order_list */
            $order_list = array_merge($sub_order_list, [$parent_order]);
            foreach ($order_list as $i => $order) {
                $order->status = 4;
                //$order->is_returnd = 1;
                $order->save(false);
            }
        }
        \Yii::$app->cache->set($cache_key, false);
        return true;
    }

    /**
     * 拼团检测退款
     * @param $event
     * @return bool
     */
    public function checkOrderTimeoutRefund($event)
    {
        $order_max = 100; //每次最多处理的团购个数，防止运行太久
        $cache_key = 'pt_order_timeout_checker';
        if (\Yii::$app->cache->get($cache_key)) {
            return true;
        }

        \Yii::$app->cache->set($cache_key, true, 10);

        /** @var Wechat $wechat */
        $wechat = isset($event->action->controller->wechat) ? $event->action->controller->wechat : null;
        if (!$wechat) {
            \Yii::$app->cache->set($cache_key, false);
            return true;
        }

        $parent_order_list_query = PtOrder::find()->where([
            'AND',
            [
                'is_group' => 1,
                'parent_id' => 0,
                'is_pay' => 1,
                'status' => 4,
                'is_returnd' => 0,
            ],
            ['IS NOT', 'limit_time', null],
            ['<=', 'limit_time', time()],
        ])->limit($order_max);
        if ($this->store_id) {
            $parent_order_list_query->andWhere(['store_id' => $this->store_id]);
        }

        /** @var PtOrder[] $parent_order_list */
        $parent_order_list = $parent_order_list_query->all();

        foreach ($parent_order_list as $parent_order) {
            $sub_order_list = PtOrder::find()->where([
                'AND',
                [
                    'is_group' => 1,
                    'parent_id' => $parent_order->id,
                    'is_pay' => 1,
                    'status' => 4,
                    'is_returnd' => 0,
                ],
            ])->andWhere(['!=', 'order_no', 'robot'])->all();

            /** @var PtOrder[] $order_list */
            $order_list = array_merge($sub_order_list, [$parent_order]);
            //VarDumper::dump($order_list, 4, 1);
            foreach ($order_list as $i => $order) {
                if ($order->pay_type == 1) {
                    $res = Refund::refund($order,$order->order_no,$order->pay_price);
                    if($res !== true){
                        return $res;
                    }
                } elseif ($order->pay_type == 3) {
                    $user = User::findOne(['id' => $order->user_id]);
                    $user->money += doubleval($order->pay_price);
                    $log = new UserAccountLog();
                    $log->user_id = $order->user_id;
                    $log->type = 1;
                    $log->price = $order->pay_price;
                    $log->desc = "拼团订单退款,订单号（{$order->order_no}）";
                    $log->order_id = $order->id;
                    $log->order_type = 6;
                    $log->addtime = time();
                    $log->save();
                    if (!$user->save()) {
                        break;
                    }
                } elseif ($order->pay_type == 2) {
                } else {
                    break;
                }
                //恢复库存
                $order_detail = PtOrderDetail::findOne(['order_id' => $order->id, 'is_delete' => 0]);
                $goods = PtGoods::findOne(['id' => $order_detail->goods_id]);
                $attr_id_list = [];
                foreach (json_decode($order_detail->attr) as $item) {
                    array_push($attr_id_list, $item->attr_id);
                }

                $goods->numAdd($attr_id_list, $order_detail->num);
                //$order->status = 4;
                $order->is_returnd = 1;
                $order->save(false);
            }

            $notice = new PtNoticeSender($wechat, $parent_order->store_id);
            $notice->sendFailNotice($parent_order->id);
        }
        \Yii::$app->cache->set($cache_key, false);
        //\Yii::$app->end();
        return true;
    }

    /**
     * 处理未在规定时间内付款的订单
     * @param ActionEvent $event
     * @return bool
     */
    private function checkNoPayOrderTimeout($event)
    {
        $order_max = 100; //每次最多处理的个数，防止运行太久
        $cache_key = 'pt_no_pay_order_timeout_checker';
        if (\Yii::$app->cache->get($cache_key)) {
            return true;
        }

        \Yii::$app->cache->set($cache_key, true, 10);
        /** @var Store $store */
        $store = isset($event->action->controller->store) ? $event->action->controller->store : null;
        if (!$store) {
            \Yii::$app->cache->set($cache_key, false);
            return true;
        }

        if (!$store->over_day || $store < 0) {
            return true;
        }

        $expire_time = intval($store->over_day) * 3600;
        /** @var PtOrder[] $order_list */
        $order_list = PtOrder::find()->where([
            'AND',
            [
                'is_pay' => 0,
                'is_cancel' => 0,
            ],
            ['<=', 'addtime', time() - $expire_time],
            ['!=', 'pay_type', 2],
        ])->limit($order_max)->all();
        foreach ($order_list as $order) {
            $order->is_cancel = 1;
            $order->save(false);
        }

        \Yii::$app->cache->set($cache_key, false);
        return true;
    }

    /**
     * 处理超时的拼团商品自动下架
     * @param ActionEvent $event
     * @return bool
     */
    private function checkGoodsTimeout($event)
    {
        $order_max = 100; //每次最多处理的个数，防止运行太久
        $cache_key = 'pt_goods_timeout_checker';
        if (\Yii::$app->cache->get($cache_key)) {
            return true;
        }

        \Yii::$app->cache->set($cache_key, true, 10);

        /** @var PtGoods[] $goods_list */
        $goods_list = PtGoods::find()->where([
            'AND',
            [
                'is_delete' => 0,
            ],
            ['>', 'limit_time', 0],
            ['<=', 'limit_time', time()],
        ])->limit($order_max)->all();

        foreach ($goods_list as $goods) {
            $goods->limit_time = 0;
            $goods->status = 2;
            $goods->save(false);
        }
        \Yii::$app->cache->set($cache_key, false);
        return true;
    }

    /**
     * 处理超时自动确认收货的订单
     * @param ActionEvent $event
     * @return bool
     */
    private function checkOrderConfirmTimeout($event)
    {
        $order_max = 100; //每次最多处理的个数，防止运行太久
        $cache_key = 'pt_order_confirm_timeout_checker';
        if (\Yii::$app->cache->get($cache_key)) {
            return true;
        }

        \Yii::$app->cache->set($cache_key, true, 10);

        /** @var Store $store */
        $store = isset($event->action->controller->store) ? $event->action->controller->store : null;
        if (!$store) {
            \Yii::$app->cache->set($cache_key, false);
            return true;
        }
        $delivery_time = intval($store->delivery_time);
        if ($delivery_time < 0) {
            $delivery_time = 0;
        }

        $expire_time = $delivery_time * 86400;

        $order_list_query = PtOrder::find()->where([
            'AND',
            [
                'is_send' => 1,
                'is_confirm' => 0,
            ],
            ['<=', 'send_time', time() - $expire_time],
            ['!=', 'pay_type', 2],
        ])->limit($order_max);
        if ($this->store_id) {
            $order_list_query->andWhere(['store_id' => $this->store_id]);
        }
        /** @var PtOrder[] $order_list */
        $order_list = $order_list_query->all();

        foreach ($order_list as $order) {
            if($order->pay_type == 2){
                $order->is_pay = 1;
            }
            $order->is_confirm = 1;
            $order->confirm_time = time();
            $order->save(false);
            $printer_order = new PinterOrder($order->store_id, $order->id, 'confirm', 2);
            $res = $printer_order->print_order();
        }

        \Yii::$app->cache->set($cache_key, false);
        return true;
    }

    /**
     * 处理拼团人数达到总成团数后拼团成功
     * @param ActionEvent $event
     * @return bool
     */
    private function checkPtOrderNumTimeout($event)
    {
        $order_max = 50;  //每次最多处理的个数，防止运行太久
        $cache_key = 'pt_order_num';
        if (\Yii::$app->cache->get($cache_key)) {
            return true;
        }
 
        \Yii::$app->cache->set($cache_key, true, 10);

        $ptOrder = PtOrder::find()->where([
            'AND',
            [
                'is_delete' => 0,
                'status' => 2,
                'is_success' => 0,
                'parent_id' => 0,
                'is_group' => 1,
                'is_delete' => 0,
                'store_id' => $this->store_id
            ]
        ])->limit($order_max)->all();

        foreach($ptOrder as $v){
            if ($v->class_group >0) {
                $ptGoods = PtGoodsDetail::findOne(['id' => $v->class_group]);
            } else {
                $order_detail = PtOrderDetail::findOne(['order_id' => $v->id, 'is_delete' => 0]);
                $ptGoods = PtGoods::findOne(['id' => $order_detail->goods_id]);
            }

            $groupNum = PtOrder::find()
                ->andWhere(['or', ['id' => $v->id], ['parent_id' => $v->id]])
                ->andWhere(['status' => 2, 'is_group' => 1])
                ->andWhere([
                    'OR',
                    ['is_pay' => 1],
                    ['pay_type' => 2]
                ])
                ->count();

            if($ptGoods->group_num - $groupNum<=0){
                $orderList = PtOrder::find()
                    ->andWhere(['or',['id'=>$v->id],['parent_id'=>$v->id]])
                    ->andWhere(['status'=>2,'is_group'=>1])
                    ->andWhere([
                        'OR',
                        ['is_pay'=>1],
                        ['pay_type'=>2]
                    ])
                    ->all();
                foreach ($orderList as $val) {
                    $val->is_success = 1;
                    $val->success_time = time();
                    $val->status = 3;
                    $val->save();
                }

                $notice = new PtNoticeSender($this->getWechat(), $v->store_id);
                $notice->sendSuccessNotice($v->id);
            }
        }

        \Yii::$app->cache->set($cache_key, false);
        return true;
    }

}
