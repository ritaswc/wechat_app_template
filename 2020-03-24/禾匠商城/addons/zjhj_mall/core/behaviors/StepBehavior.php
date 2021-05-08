<?php
namespace app\behaviors;

use app\models\StepUser;
use app\models\StepLog;
use app\models\StepActivity;
use app\models\StepRemind;
use app\models\StepSetting;

use yii\base\Behavior;
use app\models\ActivityMsgTpl;
use yii\db\Expression;

class StepBehavior extends BaseBehavior
{
    protected $only_routes = [
    // 'mch/store/index',
    'mch/step/*',
    'api/step/*'
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
            $this->remindNoticeSender($event);
        } catch (\Exception $e) {
        }
    }

    private function remindNoticeSender($event)
    {
        $time = date('H:i', time());
        $setting = StepSetting::findOne(['store_id' => $this->store->id])->remind_time;

        if ($time < $setting) {
            return true;
        }

        $cache_key = 'step_remind_checker';
        if (\Yii::$app->cache->get($cache_key)) {
            return true;
        }
        \Yii::$app->cache->set($cache_key, true, 60);

        $remind = StepRemind::findOne([
                'store_id' => $this->store->id,
                'date' => date('Y-m-d', time())
            ]);
        if ($remind) {
            return true;
        }

        $list = StepUser::find()->select('user_id')->where([
                'store_id' => $this->store->id,
                'is_delete' => 0,
                'remind' => 1,
            ])->asArray()->all();

        if ($list) {
            $ids = array_column($list, 'user_id');
            $notice = new ActivityMsgTpl($ids[0], 'STEP');
            $notice->sendRemindcNotice($ids);
        } else {
            $remind = new StepRemind();
            $remind->store_id = $this->store->id;
            $remind->user_id = 0;
            $remind->date = date('Y-m-d', time());
            $remind->save();
        }
    }

    /**
     * 处理中奖
     * @param $event
     */
    private function checkPrizeTimeout($event)
    {

        $cache_key = 'step_activity_timeout_checker';
        if (\Yii::$app->cache->get($cache_key)) {
            return true;
        }
        \Yii::$app->cache->set($cache_key, true, 60);

        $date = date('Y-m-d', time());
        $step = StepActivity::find()->where([
            'AND',
            ['store_id' => $this->store->id],
            ['is_delete' => 0],
            ['status' => 1],
            ['type' => 0]
            ])->andWhere(['<','open_date',$date])->one();

        if ($step) {
            try {
                $query = StepLog::find()->select('id,step_id')->where([
                        'AND',
                        ['store_id' => $this->store->id],
                        ['type' => 2],
                        ['status' => 2],
                        ['>=', 'num', $step->step_num],
                        ['type_id' => $step->id],
                    ]);
                $count = $query->count();
                $log = $query->asArray()->all();

                $cache_conduct = 'step_prize_conduct';
                if (\Yii::$app->cache->get($cache_conduct)) {
                    return true;
                };
                \Yii::$app->cache->set($cache_conduct, $step->id, 50);

                if (!$log) {
                    $step->type = 1;
                    $step->save();

                    //失败参与活动发送
                    $error_step_ids = StepLog::find()->select('step_id')->where([
                        'AND',
                        ['store_id' => $this->store->id],
                        ['type' => 2],
                        ['status' => 2],
                        ['<', 'num', $step->step_num],
                        ['type_id' => $step->id],
                    ])->asArray()->all();
                    $error_step_ids = array_column($error_step_ids, 'step_id');

                    if ($error_step_ids) {
                        $ids = StepUser::find()->select('user_id')->where([
                            'AND',
                            ['store_id' => $this->store->id],
                            ['in', 'id', $error_step_ids]
                            ])->column();
                        $notice = new ActivityMsgTpl(true, 'STEP');
                        $notice->sendErrorNotice($ids, ['name' => $step->name]);
                    };
                    return;
                }

                $ids = array_column($log, 'id');
                $step_ids = array_column($log, 'step_id');
                $currency = StepLog::find()->select('SUM(step_currency) as num')->where([
                        'AND',
                        ['store_id' => $this->store->id],
                        ['type' => 2],
                        ['status' => 2],
                        ['type_id' => $step->id]
                    ])->asArray()->one();

                $t = \Yii::$app->db->beginTransaction();
                $currency_num = floor((($currency['num'] + $step['currency']) / $count) * 100) / 100;
                //新增记录
                $array = [];
                foreach ($step_ids as $v) {
                    $array[] = [
                        $this->store->id, $v, 1, 2, $currency_num, $step->id, time(), time(),
                    ];
                }

                StepLog::find()->createCommand()->batchInsert(
                    StepLog::tableName(),
                    ['store_id', 'step_id', 'status', 'type', 'step_currency', 'type_id', 'raffle_time', 'create_time'],
                    $array
                )->execute();

                //增加余额
                $sql = "step_currency + " . $currency_num;
                StepUser::updateAll(
                    ['step_currency' => new Expression($sql)],
                    ['in', 'id',$step_ids]
                );
               
                $step->type = 1;
                if ($step->save()) {
                    $t->commit();
                    $ids = StepUser::find()->select('user_id')->where([
                        'AND',
                        ['store_id' => $this->store->id],
                        ['in', 'id', $step_ids]
                    ])->column();

                    $info = [
                        'name' => $step->name,
                        'currency_num' => $currency_num
                    ];
                    $notice = new ActivityMsgTpl(true, 'STEP');
                    //成功发送
                    $notice->sendSuccessNotice($ids, $info, 'success');

                    //失败参与活动发送
                    $error_step_ids = StepLog::find()->select('id,step_id')->where([
                        'AND',
                        ['store_id' => $this->store->id],
                        ['type' => 2],
                        ['status' => 2],
                        ['<', 'num', $step->step_num],
                        ['type_id' => $step->id],
                        ['not', ['in','step_id',$step_ids]],
                    ])->asArray()->all();
                    $error_step_ids = array_column($error_step_ids, 'step_id');
                  
                    $ids = StepUser::find()->select('user_id')->where([
                        'AND',
                        ['store_id' => $this->store->id],
                        ['in', 'id', $error_step_ids]
                    ])->column();
                    $notice->sendErrorNotice($ids, $info);
                } else {
                    $t->rollBack();
                }
            } catch (\Exception $e) {
                 \Yii::warning($e->getMessage());
            }
        } else {
            return true;
        }
    }
}
