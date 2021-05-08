<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/8/6
 * Time: 14:01
 */

namespace app\behaviors;


use app\models\ActivityMsgTpl;
use app\models\BargainOrder;
use app\models\Goods;
use app\models\WechatTplMsgSender;
use yii\base\Behavior;

class BargainBehavior extends BaseBehavior
{

    protected $only_routes = [
        // 'mch/store/index',
        'mch/bargain/*',
        'api/bargain/*'
    ];

    public $store_id;
    public $store;

    public function beforeAction($event)
    {
        \Yii::warning('----BARGAIN BEHAVIOR----');
        if (!empty($event->action->controller->store) && !empty($event->action->controller->store->id)) {
            $this->store = $event->action->controller->store;
        }
        try {
            $this->checkOrderTimeout($event);
            $this->checkGoods($event);
        } catch (\Exception $e) {

        }
    }

    /**
     * 处理未在规定时间内购买的订单
     * @param $event
     */
    private function checkOrderTimeout($event)
    {
        $orderMax = 100;
        $cacheKey = 'bargain_order_timeout_checker';
        if (\Yii::$app->cache->get($cacheKey)) {
            return true;
        }

        \Yii::$app->cache->set($cacheKey, true, 10);
        /* @var \app\models\BargainOrder[] $orderList */
        $orderList = BargainOrder::find()->where([
            'store_id' => $this->store->id,
            'is_delete' => 0,
            'status' => 0
        ])->limit($orderMax)->all();
        foreach ($orderList as $item) {
            if ((time() - $item->time * 3600) >= $item->addtime) {
                $attr = \Yii::$app->serializer->decode($item->attr);
                $attr_id_list = [];
                foreach ($attr as $v) {
                    $attr_id_list[] = $v['attr_id'];
                }
                /* @var Goods $goods */
                $goods = $item->goods;
                $goods->numAdd($attr_id_list, 1);
                $goods->save();
                $item->status = 2;
                $item->save();

//                $wechat = \Yii::$app->controller->wechat;
//                $msgTpl = new WechatTplMsgSender($this->store->id, $item->id, $wechat, 'BARGAIN');
//                $msgTpl->activityRefundMsg('砍价', $goods->name, '砍价活动已过期');
                $msgTpl = new ActivityMsgTpl(\Yii::$app->user->id, 'BARGAIN');
                $msgTpl->activityRefundMsg('砍价', $goods->name, '砍价活动已过期');
            }
        }
    }

    /**
     * @param $event
     * 活动到期的砍价商品自动下架
     */
    private function checkGoods($event)
    {
        $goodsMax = 100;
        $cacheKey = 'bargain_goods_timeout_checker';
        if (\Yii::$app->cache->get($cacheKey)) {
            return true;
        }

        \Yii::$app->cache->set($cacheKey, true, 10);
        /* @var \app\models\Goods[] $goodsList */
        $goodsList = Goods::find()->alias('g')->joinWith(['bargain b'])
            ->andWhere([
                'g.store_id' => $this->store->id, 'g.status' => 1,
                'g.is_delete' => 0, 'g.type' => 2
            ])->andWhere(['<=', 'b.end_time', time()])->limit($goodsMax)->all();
        foreach ($goodsList as $item) {
            $item->status = 0;
            $item->save();
        }
    }
}