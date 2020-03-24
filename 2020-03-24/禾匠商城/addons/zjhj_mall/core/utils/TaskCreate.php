<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/11/19
 * Time: 11:29
 */

namespace app\utils;

use app\models\StepActivity;
use app\models\LotteryGoods;
use app\models\task\order\OrderAuthConfirm;
use app\models\task\step\StepInfoNotice;
use app\models\task\lottery\LotteryInfoNotice;

class TaskCreate
{
    // 订单自动收货
    public static function orderConfirm($orderId, $orderType = 'STORE')
    {
        $store = \Yii::$app->store;
        $confirmTime = $store->delivery_time * 86400;
        \Yii::$app->task->create(OrderAuthConfirm::className(), $confirmTime, [
            'order_id' => $orderId,
            'order_type' => $orderType,
            'store_id' => $store->id
        ], '订单自动收货');
    }

    //步数挑战定时开奖
    public static function stepActivity($activityId)
    {
        $store = \Yii::$app->store;
        $step = StepActivity::findOne([
            'store_id' => $store->id,
            'is_delete' => 0,
            'id' => $activityId,
            'status' => 1,
            'type' => 0,
        ]);

        $saleTime = strtotime($step->open_date) - time() > 0 ? strtotime($step->open_date) - time() : 10;
        \Yii::$app->task->create(StepInfoNotice::className(), $saleTime, [
            'activity_id' => $step->id,
            'store_id' => $store->id
        ], '步数挑战定时开奖');
    }

    //抽奖定时开奖
    public static function lotteryGoods($goodsId)
    {
        $store = \Yii::$app->store;
        $lottery = LotteryGoods::findOne([
            'store_id' => $store->id,
            'is_delete' => 0,
            'status' => 1,
            'type' => 0,
            'id' => $goodsId,
        ]);

        $saleTime = $lottery->end_time - time() > 0 ? $lottery->end_time - time() : 10;
        \Yii::$app->task->create(LotteryInfoNotice::className(), $saleTime, [
            'lottery_id' => $lottery->id,
            'store_id' => $store->id
        ], '抽奖定时开奖');
    }
}
