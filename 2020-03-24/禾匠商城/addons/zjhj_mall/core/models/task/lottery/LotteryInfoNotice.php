<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: xay
 */

namespace app\models\task\lottery;

use app\hejiang\task\TaskRunnable;
use app\models\Store;
use app\models\ActionLog;
use app\models\LotteryReserve;
use app\models\LotteryGoods;
use app\models\LotteryLog;
use app\models\ActivityMsgTpl;

class LotteryInfoNotice extends TaskRunnable
{
    public $store;
    public $params = [];

    public function run($params = [])
    {
        $this->store = Store::findOne($params['store_id']);
        \Yii::$app->store =  $this->store;
        $this->params = $params;
        return $this->check();
    }


    /**
     * 存储错误日志
     * @param $e
     * @return bool
     */
    public function saveActionLog($e)
    {
        // 记录错误信息
        $errorInfo = [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'trace' => $e->getTraceAsString(),
        ];

        $actionLog = new ActionLog();
        $actionLog->store_id = $this->params['store_id'];
        $actionLog->title = '定时任务';
        $actionLog->addtime = time();
        $actionLog->admin_name = '系统自身';
        $actionLog->admin_id = 0;
        $actionLog->admin_ip = '';
        $actionLog->route = '';

        $actionLog->action_type = '抽奖定时触发失败';
        $actionLog->obj_id = $this->params['lottery_id'];
        $actionLog->result = json_encode($errorInfo);
        $actionLog->save();

        return false;
    }

    /**
     * 处理中奖
     * @param $event
     */
    private function check()
    {
        $lottery = LotteryGoods::find()->where([
                'AND',
                ['store_id' => $this->store->id],
                ['is_delete' => 0],
                ['status' => 1],
                ['type' => 0],
                ['id' => $this->params['lottery_id']],
                ['<=', 'end_time', time()]
            ])->with('goods')->one();
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
                        $notice = new ActivityMsgTpl(true, 'LOTTERY');
                        $notice->sendLotterySuccNotice($user_succ, ['name' => $lottery->goods->name]);
                        $notice->sendLotteryErrNotice($user_err, ['name' => $lottery->goods->name]);
                        return true;
                    } else {
                        $t->rollBack();
                        return false;
                    }
                } else {
                    $t = \Yii::$app->db->beginTransaction();
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

                    LotteryLog::updateAll(['status' => 2,'obtain_time' => time()], [
                        'id' => $idList,
                    ]);

                    $lottery->type = 1;
                    if ($lottery->save()) {
                        $t->commit();
                        $notice = new ActivityMsgTpl(true, 'LOTTERY');
                        $notice->sendLotterySuccNotice($user_succ, ['name' => $lottery->goods->name]);
                        return true;
                    } else {
                        $t->rollBack();
                        return false;
                    }
                }
            } catch (\Exception $e) {
                \Yii::warning($e->getMessage());
                $this->saveActionLog($e);
                return false;
            }
        } else {
            return false;
        }
    }
}
