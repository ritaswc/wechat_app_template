<?php
namespace app\modules\api\models\lottery;

use app\hejiang\ApiCode;
use app\modules\api\models\ApiModel;
use app\models\LotteryGoods;
use app\models\LotteryLog;
use app\models\LotterySetting;

class LotteryLogForm extends ApiModel
{
    public $store_id;
    public $lottery_id;
    public $id;
    public $user;
    public $status;
    public $form_id;
    public $page;
    public $limit;
    public $page_num;
    public $child_id;
    public $user_id;

    public function rules()
    {
        return [
            [['store_id', 'lottery_id', 'status', 'id', 'page_num', 'page', 'limit', 'child_id', 'user_id'], 'integer'],
            [['form_id'], 'string'],
            [['limit'], 'default', 'value' => 5],
            [['page'], 'default', 'value' => 1],
            [['child_id'], 'default', 'value' => 0],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'user_id' => '用户ID',
            'lottery_id' => '抽奖ID',
            'addtime' => '创建时间',
            'status' => '0待开奖 1未中奖 2中奖3已领取',
            'goods_id' => '商品id',
            'attr' => '规格',
            'raffle_time' => '领取时间',
            'order_id' => '订单ID',
            'obtain_time' => '获取时间',
            'form_id' => 'Form ID',
            'child_id' => '下级ID',
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        if ($this->status==2) {
            $status = [2,3];
        } else {
            $status = [$this->status];
        }

        $offset = $this->limit * ($this->page - 1);

        if ($this->status==1) {
            $orderQuery = LotteryLog::find()->select('lottery_id')->where('ll.user_id = user_id and ll.lottery_id = lottery_id')->andWhere(['status'=>[2,3]]);
            $list = LotteryLog::find()->alias('ll')->where([
                'AND',
                ['store_id' => $this->store->id],
                ['user_id' => $this->user->id],
                ['not in', 'lottery_id', $orderQuery],
                ['status'=>1]
            ])->with(['lottery','gift'])->orderBy('status DESC,addtime DESC,id DESC')->groupBy('lottery_id')->limit($this->limit)->offset($offset)->asArray()->all();
            //dd($list);
        }
        if ($this->status==2) {
            $list = LotteryLog::find()->where([
                'AND',
                ['store_id' => $this->store->id],
                ['user_id' => $this->user->id],
                ['in','status',$status],
            ])->with(['lottery','gift'])->orderBy('status desc')->groupBy('lottery_id')->limit($this->limit)->offset($offset)->orderBy('addtime DESC,id DESC')->asArray()->all();

            foreach ($list as &$v) {
                if ($v['child_id']!=0) {
                    $data = LotteryLog::find()->where([
                        'AND',
                        ['store_id' => $this->store->id],
                        ['user_id' => $v['user_id']],
                        ['lottery_id' => $v['lottery_id']],
                        ['child_id' => 0],
                    ])->one();
                    $v['id'] = $data['id'];
                }
            };
            unset($v);
        };
        if ($this->status==0) {
            $list = LotteryLog::find()->alias('g')->where([
                'g.store_id' => $this->store->id,
                'g.user_id' => $this->user->id,
                'g.status' => 0,
                'g.child_id' => 0,
            ])->joinwith(['lottery l'=>function ($query) {
                $query->where([
                    'l.is_delete' => 0,
                ]);
            }])->with(['gift'])->limit($this->limit)->offset($offset)->orderBy('g.addtime ASC,g.id DESC')->asArray()->all();
        };

        foreach ($list as &$v) {
            $v['time'] = date('Y.m.d H.i 开奖', $v['lottery']['end_time']);
        }
        unset($v);

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'list' => $list,
            ],
        ];
    }


    private function selectId($id)
    {
            $list = LotteryLog::find()->where([
                'store_id' => $this->store_id,
                'id' => $id,
                'user_id' => $this->user->id,
            ])->with(['gift'=>function ($query) {
                    $query->select('name,attr,original_price,cover_pic');
            }])->with(['lottery'=>function ($query) {
                    $query->select('stock,end_time,id');
            }])->asArray()->one();

        if ($list['status'] == 1) {
            $child = LotteryLog::find()->where([
                    'AND',
                    ['store_id' => $this->store_id],
                    ['user_id' => $this->user->id],
                    ['lottery_id' => $list['lottery_id']],
                    ['in','status',[2,3]]
                ])->asArray()->one();
            if ($child) {
                $list['status'] = $child['status'];
                $list['lucky_code'] = $child['lucky_code'];
            }
        }

            $list['time'] = date('m月d日 H:i开奖', $list['lottery']['end_time']);

            $list['parent_num'] = LotteryLog::find()->where([
                                    'AND',
                                    ['store_id' => $this->store_id],
                                    ['user_id' => $this->user->id],
                                    ['lottery_id' => $list['lottery_id']],
                                    ['not', ['child_id' => 0]],
                                    ['not', ['status' => -1]]
                                ])->count();

            $query = LotteryLog::find()->where(['store_id' => $this->store_id,'lottery_id' => $list['lottery_id'], 'child_id' => 0]);
            $num = $query->count();

        if (in_array($list['status'], [1,2,3])) {
            $limit = 6;
            $offset = $limit * ($this->page_num -1);
            $query = LotteryLog::find()->where(['store_id' => $this->store_id,'lottery_id' => $list['lottery_id']])->andWhere(['in','status',[2,3]])->offset($offset);

            $list['pe_num'] = $query->count();
        } else {
            $limit = 30;
        }

            $user_list = $query->with(['user'])->limit($limit)->orderBy('addtime DESC')->asArray()->all();

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'data' => [
                    'list' => $list,
                    'num' => $num,
                    'user_list' => $user_list,
                ],
            ];
    }


    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        };

        if ($this->id) {
            return $this->selectId($this->id);
        };
        if ($this->lottery_id) {
            $goods = LotteryGoods::find()->where([
                    'store_id' => $this->store_id,
                    'id' => $this->lottery_id,
                    'is_delete' => 0
                ])->with(['log'=>function ($query) {
                    $query->where([
                        'store_id' => $this->store->id,
                        'user_id' => $this->user->id,
                        'child_id' => 0
                    ]);
                }])->asArray()->one();

            $log = $goods['log'];

            if (count($log)) {
                return $this->selectId($log[0]['id']);
            } else {
                if ($goods['end_time'] < time() || $goods['start_time'] > time()) {
                    return [
                        'code' => 1,
                        'msg' => '活动已结束'
                    ];
                }
                $model = new LotteryLog();

                $model->store_id = $this->store_id;
                $model->addtime = time();
                $model->user_id = $this->user->id;
                $model->status = 0;
                $model->form_id = $this->form_id;
                $model->attr = $goods['attr'];
                $model->lottery_id = $this->lottery_id;
                $model->goods_id = $goods['goods_id'];
                $model->child_id = 0;
                $model->lucky_code = (string)$this->setlucky($this->lottery_id);
  
                if ($model->save()) {
                    $parent = LotteryLog::findOne([
                        'store_id' => $this->store_id,
                        'lottery_id' => $this->lottery_id,
                        'status' => -1,
                        'child_id' => $this->user->id
                    ]);
                    if ($parent) {
                        $parent->status = 0;
                        $parent->save();
                    };

                    return $this->selectId($model->id);
                } else {
                    return $this->getErrorResponse($model);
                }
            }
        }
    }

    public function luckyCode()
    {
        $id = $this->id;
        $award = [];
        $own = LotteryLog::find()->where([
                'store_id' => $this->store_id,
                'id' => $id,
                'user_id' => $this->user->id,
            ])->with(['user'=>function ($query) {
                $query->select("nickname,avatar_url");
            }])->asArray()->one();

        if ($own['status'] == 1) {
            $child = LotteryLog::find()->select('*,child_id as user_id')->where([
                    'AND',
                    ['store_id' => $this->store_id],
                    ['user_id' => $this->user->id],
                    ['lottery_id' => $own['lottery_id']],
                    ['in','status',[2,3]]
                ])->with(['user'=>function ($query) {
                        $query->select("nickname,avatar_url");
                }])->asArray()->one();
            if ($child) {
                $award = $child;
            }
        }

        if ($own['status'] == 2 || $own['status'] == 3) {
            $award = $own;
        }

        $limit = 9;
        $offset = $limit * ($this->page -1);

        $query = LotteryLog::find()->select('l.lucky_code,u.nickname,u.avatar_url,')->alias('l')->where([
                'AND',
                ['l.store_id' => $this->store_id],
                ['l.user_id'=>$this->user->id],
                ['l.lottery_id'=>$own['lottery_id']],
                ['not', ['l.child_id' => 0]],
                ['not', ['l.lucky_code' => $award['lucky_code']]],
                ['not', ['l.status' => -1]],
            ]);
        $num = $query->count() + 1;
        $parent = $query->limit($limit)->offset($offset)->joinwith('childId u')->asArray()->all();

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'own' => $own,
                'parent' => $parent,
                'award' => $award,
                'num' => $num,
            ],
        ];
    }

    private function random_num($length = 6)
    {
        $min = pow(10, ($length - 1));
        $max = pow(10, $length) - 1;
        return mt_rand($min, $max);
    }

    private function setlucky($lottery_id)
    {
        $lucky_code = $this->random_num(6);
        $list = LotteryLog::find()
                 ->where(['store_id' => $this->store_id,
                          'lottery_id' => $lottery_id,
                          'lucky_code' => $lucky_code,
                          ])->one();
        if ($list==null) {
            return $lucky_code;
        } else {
            $this->setlucky($lottery_id);
        }
    }

    public function clerk()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        };
        if ($this->user->id == $this->user_id) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '本人不可参与'
            ];
        };
        if (!$this->user->id) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '尚未授权'
            ];
        };
        $parent = LotteryLog::findOne([
            'store_id' => $this->store_id,
            'lottery_id' => $this->lottery_id,
            'user_id' => $this->user_id,
            'child_id' => 0,
        ]);

        if (empty($parent)) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '邀请用户未参与'
            ];
        };

        $self = LotteryLog::findOne([
            'store_id' => $this->store_id,
            'lottery_id' => $this->lottery_id,
            'user_id' => $this->user->id,
            'child_id' => 0,
        ]);

        if ($self) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '用户未参与'
            ];
        };

        $model = LotteryLog::find()->where([
                'store_id' => $this->store_id,
                'lottery_id' => $this->lottery_id,
                'child_id' => $this->user->id,
            ])->andWhere(['in','status',[-1,0]])->one();

        if ($model) {
            if ($model->status==0) {
                return [
                    'code' => ApiCode::CODE_ERROR,
                    'msg' => '用户已参与'
                ];
            }
        } else {
            $model = new LotteryLog();
        }

        $goods = LotteryGoods::findOne(['store_id' => $this->store_id, 'id' => $this->lottery_id]);
        $setting = LotterySetting::findOne(['store_id' => $this->store->id]);

        if ($setting->type == 1) {
            $model->status = -1;
        } else {
            $model->status = 0;
        }
        $model->store_id = $this->store_id;
        $model->addtime = time();
        $model->user_id = $this->user_id;
        $model->attr = $goods->attr;
        $model->lottery_id = $this->lottery_id;
        $model->goods_id = $goods->goods_id;
        $model->child_id = $this->user->id;
        $model->lucky_code = (string)$this->setlucky($this->lottery_id);

        if ($model->save()) {
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '参加成功'
            ];
        } else {
            return $this->getErrorResponse($model);
        }
    }
}
