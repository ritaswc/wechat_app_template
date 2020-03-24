<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: xay
 */

namespace app\models\task\step;

use app\hejiang\task\TaskRunnable;
use app\models\Store;
use app\models\ActionLog;
use yii\db\Expression;
use app\models\StepLog;
use app\models\StepUser;
use app\models\StepActivity;
use app\models\ActivityMsgTpl;

class StepInfoNotice extends TaskRunnable
{
    public $store;
    public $time;
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

        $actionLog->action_type = '步数挑战定时触发失败';
        $actionLog->obj_id = $this->params['activity_id'];
        $actionLog->result = json_encode($errorInfo);
        $actionLog->save();

        return false;
    }

    /**
     * 处理中奖
     * @param
     */
    private function check()
    {
        $date = date('Y-m-d', time());
     
        $step = StepActivity::find()->where([
            'AND',
            ['store_id' => $this->store->id],
            ['is_delete' => 0],
            ['id' => $this->params['activity_id']],
            ['status' => 1],
            ['type' => 0],
            ['<', 'open_date', $date]
            ])->one();
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
                    return true;
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
                $currency_num = floor(($currency['num'] / $count + $step['currency']) * 100) / 100;
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
                    return true;
                } else {
                    $t->rollBack();
                    return false;
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
