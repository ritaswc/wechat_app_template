<?php
namespace app\modules\api\models\step;

use app\hejiang\ApiCode;
use app\modules\api\models\ApiModel;
use app\models\StepLog;
use app\models\StepUser;
use app\models\StepActivity;
use app\models\Pic;
use app\models\Ad;
use app\models\StepSetting;

class StepLogForm extends ApiModel
{
    public $id;
    public $store_id;
    public $create_time;
    public $num;
    public $step_currency;
    public $step_id;
    public $type;
    public $order_id;

    public $limit;
    public $page;
    public $user;
    public $status;

    public $activity_id;
    public $remind;

    public function rules()
    {
        return [
            [['limit', 'page', 'store_id', 'create_time', 'num', 'step_id', 'type', 'order_id', 'status', 'activity_id', 'remind'], 'integer'],
            [['step_currency'], 'number'],
            [['status'], 'in', 'range' => [1,2,3]],
            [['limit'], 'default', 'value' => 10],
            [['page'], 'default', 'value' => 1]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'create_time' => '创建时间',
            'num' => '兑换布数',
            'step_currency' => '活力币',
            'step_id' => 'User ID',
            'type' => '1兑换 2支出 ',
            'order_id' => '订单ID',
            'status' => '状态'
        ];
    }

    public function ranking()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        };
        $user = StepUser::find()->where([
                'store_id' => $this->store_id,
                'user_id' => $this->user->id,
                'is_delete' => 0,
            ])->with('user')->asArray()->one();
        if (!$user) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '用户不存在'
            ];
        };
        $offset = $this->limit * ($this->page - 1);
        if ($this->status == 1) {
            $query = StepUser::find()->where([
                'user_id' => $user['user_id']
            ])->orWhere([
                'parent_id' => $user['id']
            ])->orWhere([
                'id' => $user['parent_id']
            ])->andWhere([
                'AND',
                ['>', 'step_currency', 0],
                ['store_id' => $this->store_id],
                ['is_delete' => 0]
            ])
            ->with('user')
            ->limit($this->limit)
            ->orderBy('step_currency desc')
            ->offset($offset);
        };
        if ($this->status == 2) {
            //全国限制
            $setting = StepSetting::find()->where(['store_id' => $this->store_id])->one();
            if ($setting->ranking_num && $offset + $this->limit > $setting->ranking_num) {
                $this->limit = $setting->ranking_num - $offset > 0 ? $setting->ranking_num - $offset : 0;
            };

            $query = StepUser::find()->where([
                'store_id' => $this->store_id,
                'is_delete' => 0
            ])
            ->with('user')
            ->limit($this->limit)
            ->orderBy('step_currency desc')
            ->offset($offset);
        };
        $list = $query->andWhere(['>', 'step_currency', 0])->asArray()->all();
        $user['raking'] = $query->andWhere(['>=','step_currency',$user[step_currency]])->count();
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'list' => $list,
                'user' => $user,
                'ad_data' => $this->adData(3)
            ],
        ];
    }

    private function adData($type)
    {
        $ad = Ad::findOne(['store_id' => $this->store_id, 'type' => $type,'is_delete' => 0, 'status' => 1]);
        return $ad;
    }

    public function activityJoin()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        };
        $date = date('Y-m-d', time());
        $list = StepActivity::find()->where([
                'AND',
                ['store_id' => $this->store_id],
                ['>','open_date',$date],
                ['is_delete' => 0],
                ['id' => $this->activity_id],
                ['type' => 0],
                ['status' => 1]
        ])->one();

        if (!$list) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '活动已过期或不存在'
            ];
        }
        $user = StepUser::findOne([
                'user_id' => $this->user->id,
                'is_delete' => 0,
            ]);

        $log = StepLog::findOne([
                'store_id' => $this->store_id,
                'type' => 2,
                'status' => 2,
                'step_id' => $user->id,
                'type_id' => $list->id,
            ]);
        if ($log) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '已参加'
            ];
        }
        $bail_currency = $list->bail_currency;

        if ($bail_currency > $user->step_currency) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '活力币不足'
            ];
        }

        $t = \Yii::$app->db->beginTransaction();
        $model = new StepLog();
        $model->create_time = time();
        $model->store_id = $this->store_id;
        $model->step_id = $user->id;
        $model->status = 2;
        $model->type = 2;
        $model->type_id = $list->id;
        $model->step_currency = $bail_currency;
        $model->save();

        $user->step_currency = round($user->step_currency - $bail_currency, 2);
        if ($user->save()) {
            $t->commit();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'list' => [
                    'id' => $list->id,
                    'bail_currency' =>  $bail_currency,
                    'user_currency' => $user->step_currency,
                ]
            ];
        } else {
            $t->rollBack();
            return $this->getErrorResponse($user);
        }
    }

    public function activityDetail()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        };
        $list = StepActivity::find()->where([
                'AND',
                ['store_id' => $this->store_id],
                ['is_delete' => 0],
                ['id' => $this->activity_id],
                ['type' => 0],
                ['status' => 1]
        ])->asArray()->one();

        if (!$list) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '活动已过期或不存在'
            ];
        };

        $list['open_date'] = str_replace('-', '.', $list['open_date']);
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'list' => $list,
            ]
        ];
    }

    public function activityLog()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        };
        $user = StepUser::findOne([
            'store_id' => $this->store_id,
            'user_id' => $this->user->id,
            'is_delete' => 0,
        ]);

        if (!$user) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '用户不存在'
            ];
        }

        $info = StepLog::find()->alias('s')->select("SUM(s.step_currency) as currency,count(s.id) as bout")->where([
            'AND',
            ['s.store_id' => $this->store_id],
            ['s.step_id' => $user->id],
            ['s.type' => 2],
            ['s.status' => 1],
        ])->joinWith(['activity l' => function ($query) use ($user) {
                $query->where([
                    'AND',
                    ['l.store_id' => $this->store_id],
                    ['l.is_delete' => 0],
                    ['not',['l.type' => 2]]
                    ]);
        }])->groupBy('type_id')->asArray()->one();

        $query = StepLog::find()->alias('s')->where([
                's.store_id' => $this->store_id,
                's.type' => 2,
                's.step_id' => $user->id
            ])->joinWith(['activity l' => function ($query) use ($user) {
                $query->where([
                    'AND',
                    ['l.store_id' => $this->store_id],
                    ['l.is_delete' => 0],
                    ['not',['l.type' => 2]]
                    ]);
            }])->groupBy('type_id');
        $info['total_bout'] = $query->count();
        $info['currency'] = $info['currency'] ? $info['currency'] : 0;
        $info['bout'] = $info['bout'] ? $info['bout'] : 0;
        $info['bout_ratio'] = $info['total_bout'] ? floor($info['bout'] / $info['total_bout'] * 100) : 0;

        $query = StepActivity::find()->alias('a')->where([
                'AND',
                ['a.store_id' => $this->store_id],
                ['a.is_delete' => 0],
                ['not',['a.type' => 2]]
            ])->joinWith(['log l' => function ($query) use ($user) {
                $query->where([
                    'l.store_id' => $this->store_id,
                    'l.type' => 2,
                    'l.step_id' => $user->id
                    ]);
            }]);
            $offset = $this->limit * ($this->page - 1);
            $list = $query->limit($this->limit)->offset($offset)->orderBy('open_date desc')->asArray()->all();

        foreach ($list as &$item) {
            $item['log_status'] = $this->deep_in_array($item['log'], $item['step_num'], $user_num, $user_currency);
            $item['user_num'] = $user_num;
            $item['user_currency'] = $user_currency;
        };
            unset($v);
            unset($user_num);
            unset($user_currency);
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'list' => $list,
                'info' => $info
            ]
        ];
    }

    private function deep_in_array($item, $step_num, &$user_num = null, &$user_currency = null)
    {
        $user_num  = 0;
        $user_currency = 0;
        if (!$item) {
            return 4; //未参与
        }
        foreach ($item as $v) {
            if ($v['status'] == 1) {
                $user_currency = $v['step_currency'];
                return 1; //成功兑换
            };
            $user_num = max($user_num, $v['num']);
        };
        if ($user_num >= $step_num) {
            return 2; //已达标
        } else {
            return 3; // 已参与
        }
    }

    public function remind()
    {
        $user = StepUser::findOne([
                'store_id' => $this->store_id,
                'user_id' => $this->user->id,
                'is_delete' => 0
            ]);
        $user->remind = $this->remind;
        if ($user->save()) {
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => 'success'
            ];
        } else {
            return $this->getErrorResponse($user);
        }
    }

    public function picList()
    {
        $this->limit = 4;
        $offset = $this->limit * ($this->page - 1);

        $pic_list = Pic::find()->select('id,pic_url')->limit($this->limit)->offset($offset)->where(['store_id' => $this->store_id, 'is_delete' => 0, 'type' => 1])->all();

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'pic_list' => $pic_list
            ],
        ];
    }

    public function inviteDetail()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $user = StepUser::findOne([
            'store_id' => $this->store_id,
            'user_id' => $this->user->id,
            'is_delete' => 0
        ]);
        
        $query = StepUser::find()->alias('i')->select('i.id,u.nickname,u.avatar_url,i.create_time,i.invite_ratio')->where([
            'i.store_id' => $this->store_id,
            'i.parent_id' => $user->id,
            'i.is_delete' => 0,
        ])->joinWith(['user u']);

        $count = $query->count();
        $this->limit = 15;
        $offset = $this->limit * ($this->page - 1);
        $query1 = clone $query;
        $now_count = $query1->andWhere(['>','i.create_time',strtotime(date("Y-m-d"))])->count();
        $invite_list = $query->orderBy(['i.create_time' => SORT_DESC])->limit($this->limit)->offset($offset)->groupBy('i.user_id')->asArray()->all();

        foreach ($invite_list as &$v) {
            $v['invite_time'] = date('Y-m-d', $v['create_time']);
        };

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'invite_list' => $invite_list,
                'info' => [
                    'count' => $count,
                    'now_count' => $now_count
                ]
            ],
        ];
    }
    
    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $user = StepUser::findOne([
                'store_id' => $this->store_id,
                'user_id' => $this->user->id,
                'is_delete' => 0
            ]);

        if ($this->status == 1) {
            $query = StepLog::find()->select('l.id,l.step_currency,l.create_time,l.status,l.type,l.type_id')->alias('l')->where([
                'l.store_id' => $this->store_id,
                'l.status' => 1,
                'l.step_id' => $user->id,
            ])->with('activity');
        } elseif ($this->status == 2) {
            $query = StepLog::find()->select('l.id,l.step_currency,g.name,l.create_time,l.status,l.type,l.type_id')->alias('l')->where([
                'l.store_id' => $this->store_id,
                'l.status' => 2,
                'l.step_id' => $user->id,
            ])->joinWith(['order o' => function ($query) {
                $query->joinWith(['goods g']);
            }])->with('activity');
        }

        $offset = $this->limit * ($this->page - 1);
        $log = $query->orderBy(['l.create_time' => SORT_DESC])->limit($this->limit)->offset($offset)->asArray()->all();

        foreach ($log as &$v) {
            $v['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
        };
        unset($v);

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'user' => $user,
                'log' => $log
            ],
        ];
    }
}
