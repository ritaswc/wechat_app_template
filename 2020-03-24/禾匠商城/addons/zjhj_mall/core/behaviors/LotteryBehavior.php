<?php
namespace app\behaviors;

use app\models\LotteryReserve;
use app\models\LotteryGoods;
use app\models\LotteryLog;
use app\models\Goods;
use yii\base\Behavior;
use app\models\ActivityMsgTpl;

class LotteryBehavior extends BaseBehavior
{
    protected $only_routes = [
    // 'mch/store/index',
    'mch/lottery/*',
    'api/lottery/*'
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
            $this->checkPrizeTimeout($event);
        } catch (\Exception $e) {
        }
    }


    /**
     * 处理中奖
     * @param $event
     */
    private function checkPrizeTimeout($event)
    {
        $cache_key = 'lottery_prize_timeout_checker';
        if (\Yii::$app->cache->get($cache_key)) {
            return true;
        }
        \Yii::$app->cache->set($cache_key, true, 30);

        /** @var Wechat $wechat */
        $wechat = isset($event->action->controller->wechat) ? $event->action->controller->wechat : null;
        if (!$wechat) {
            \Yii::$app->cache->set($cache_key, false);
            return true;
        }

        $lottery = LotteryGoods::find()->where([
            'store_id' => $this->store->id,
            'is_delete' => 0,
            'status' => 1,
            'type' => 0
            ])->with('goods')->andWhere(['<=','end_time',time()])->one();
        if ($lottery) {
            try {
                $reserve = LotteryReserve::find()->select('user_id')->where([
                    'store_id' => $this->store->id,
                    'lottery_id' => $lottery->id,
                    ])->column();

                $query = LotteryLog::find()->select('id,user_id')->where([
                            'store_id' => $this->store->id,
                            'lottery_id' => $lottery->id,
                            'status' => 0,
                            'child_id' => 0,
                        ]);
                $count = $query->count();//参与人数

                $stock = $lottery->stock;//奖品数量
              
                if ($count > $stock) {
                    $log = $query->asArray()->all();
                    $logs = array_column($log, 'user_id', 'id'); //参与详情
                    $same = array_intersect($logs, $reserve); //中奖名单1
                    $ids_a = array_keys($same);
      
                    //$num = $lottery->stock-count($same); //剩余数量
                    //lucky_code
                    $lucky_logs = LotteryLog::find()->select('id,user_id,child_id')->where([
                                'store_id' => $this->store->id,
                                'lottery_id' => $lottery->id,
                                'status' => 0,
                            ])->andWhere(['not', ['in','child_id',$same]])->asArray()->all();
                    $lucky_log = array_column($lucky_logs, 'user_id', 'id');
              
                    if (count($same) > $stock) {
                        $new_ids = array_splice($ids_a, 0, $stock);
                    } elseif (count($same) == $stock) {
                        $new_ids = $ids_a;
                    } elseif (count($same) + 1 == $stock) {
                        //随机值
                        $new_logs = array_diff($lucky_log, $same);
                        $ids_b = array_rand($new_logs, 1);
                        array_push($ids_a, $ids_b);

                        $new_ids = $ids_a;
                    } else {
                        $num = $stock - count($same);
                        //随机值
                        $new_logs = array_diff($lucky_log, $same);

                        //$ids_b = array_rand($new_logs,$num);
                        $ids_b = [];
                        $array = [];
                        while ($num > count($ids_b)) {
                            $ids = array_rand($new_logs, 1);
                            $user_id = $new_logs[$ids];

                            if (in_array($user_id, $array)) {
                                continue;
                            } else {
                                $array[] = $user_id;
                                $ids_b[] = $ids;
                            }
                        }
                        $new_ids = array_merge($ids_a, $ids_b);
                    }

                    $cache_conduct = 'lottery_prize_conduct';
                    if (\Yii::$app->cache->get($cache_conduct)) {
                        return true;
                    };
                    \Yii::$app->cache->set($cache_conduct, $lottery->id, 50);
                 
                    $t = \Yii::$app->db->beginTransaction();
                    //批量修改
                    $idList_info = LotteryLog::find()->select('id,user_id')
                            ->where([
                                'AND',
                                ['store_id' => $this->store->id],
                                ['lottery_id' => $lottery->id],
                                ['status' => 0],
                                ['in','id',$new_ids],
                            ])->asArray()->all();
          
                    $idList = array_column($idList_info, 'id');
                    $user_succ = array_column($idList_info, 'user_id');

                    LotteryLog::updateAll(['status' => 2,'obtain_time' => time()], [
                        'id' => $idList,
                    ]);

                    //无获奖
                    $idList_info = LotteryLog::find()->select('id,user_id')
                            ->where([
                                'AND',
                                ['store_id' => $this->store->id],
                                ['lottery_id' => $lottery->id],
                                ['status' => 0],
                            ])->asArray()->all();

                    $idList_err = array_column($idList_info, 'id');
                    $user_err = array_column($idList_info, 'user_id');

                    $user_err = array_diff(array_unique($user_err), $user_succ);

                    LotteryLog::updateAll(['status' => 1,'obtain_time' => time()], [
                        'id' => $idList_err,
                    ]);
                    $lottery->type = 1;
                    if ($lottery->save()) {
                        $t->commit();
                        $notice = new ActivityMsgTpl(1, 'LOTTERY');
                        $notice->sendLotterySuccNotice($user_succ, ['name' => $lottery->goods->name]);
                        $notice->sendLotteryErrNotice($user_err, ['name' => $lottery->goods->name]);
                    } else {
                        $t->rollBack();
                    }
                } else {
                    $t = \Yii::$app->db->beginTransaction();
                    $cache_conduct = 'lottery_prize_conduct';
                    if (\Yii::$app->cache->get($cache_conduct)) {
                        return true;
                    };
                    \Yii::$app->cache->set($cache_conduct, $lottery->id, 30);


                    //批量修改
                    $idList_info = LotteryLog::find()->select(['id', 'user_id'])
                    ->where([
                        'AND',
                        ['store_id' => $this->store->id],
                        ['lottery_id' => $lottery->id],
                        ['status' => 0],
                        ['child_id' => 0],
                    ])->asArray()->all();

                    $idList = array_column($idList_info, 'id');
                    $user_succ = array_column($idList_info, 'user_id');

                    LotteryLog::updateAll(['status' => 2,'obtain_time' => time(), ], [
                        'id' => $idList,
                    ]);

                    $lottery->type = 1;
                    if ($lottery->save()) {
                        $t->commit();
                        $notice = new ActivityMsgTpl(1, 'LOTTERY');
                        $notice->sendLotterySuccNotice($user_succ, ['name' => $lottery->goods->name]);
                    } else {
                        $t->rollBack();
                    }
                }
            } catch (\Exception $e) {
                 \Yii::warning($e->getMessage());
            }
        } else {
            return true;
        }
    }
}
