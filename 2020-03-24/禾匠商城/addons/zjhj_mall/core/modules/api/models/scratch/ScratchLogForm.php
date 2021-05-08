<?php
namespace app\modules\api\models\scratch;

use app\modules\api\models\ApiModel;
use app\models\ScratchLog;
use app\models\ScratchSetting;
use yii\data\Pagination;
use app\models\UserAccountLog;
use app\models\User;
use app\models\Coupon;
use app\models\UserCoupon;
use app\models\Register;

class ScratchLogForm extends ApiModel
{
    public $user_id;
    public $store_id;
    public $page;
    public $limit;
    public $id;

    public function log()
    {
        $list = ScratchLog::find()
            ->where([
                'store_id' => $this->store_id,
            ])->andWhere(['<>','type',5])
              ->andWhere(['<>','status',0])
              ->with(['gift' => function ($query) {
                $query->where([
                    'store_id' => $this->store_id,
                    'is_delete' => 0
                ]);
              }])->with(['coupon' => function ($query) {
                $query->where([
                  'store_id' => $this->store_id,
                  'is_delete' => 0
                ]);
              }])->with(['user' => function ($query) {
                $query->where([
                  'store_id' => $this->store_id,
                  'is_delete' => 0
                ]);
              }])->limit(2)->orderBy('create_time DESC,id DESC')->asArray()->all();

        if (!empty($list)) {
            foreach ($list as &$v) {
                $v['type'] = (int)$v['type'];
                $v['create_time'] = date('m-d H:i', $v['create_time']);

                switch ($v['type']) {
                    case 1:
                        $v['name'] = $v['price'] . '元余额';
                        break;
                    case 2:
                        $v['name'] = $v['coupon'] = $v['coupon']['name'];
                        break;
                    case 3:
                        $v['name'] = $v['num'] . '积分';
                        break;
                    case 4:
                        $v['name'] = $v['gift'] = $v['gift']['name'];
                        break;
                    case 5:
                        $v['name'] = '谢谢参与';
                        break;
                    default:
                }

                if ($v['user']) {
                    $v['user'] =  $v['user']['nickname'];
                }
            }
            unset($v);
        }
            
        return [
            'code' => 0,
            'data' => $list
        ];
    }
    public function search()
    {

        $form = ScratchLog::find()
            ->where([
                'store_id' => $this->store_id,
                'user_id' => $this->user_id,
            ])->andWhere(['<>','type','5'])
              ->andWhere(['<>','status',0])
              ->with(['gift' => function ($query) {
                $query->where([
                    'store_id' => $this->store_id,
                    'is_delete' => 0
                ]);
              }])->with(['coupon' => function ($query) {
                $query->where([
                  'store_id' => $this->store_id,
                  'is_delete' => 0
                ]);
              }]);

        $count = $form->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1, 'pageSize' => $this->limit]);

        $list = $form->limit($pagination->limit)->offset($pagination->offset)->orderBy('create_time DESC,id DESC')->asArray()->all();
        foreach ($list as &$v) {
            $v['create_time'] = date('Y:m:d H:i:s', $v['create_time']);

            switch ($v['type']) {
                case 1:
                    $v['name'] = $v['price'] . '元余额';
                    break;
                case 2:
                    $v['name'] = $v['coupon']['name'];
                    break;
                case 3:
                    $v['name'] = $v['num'] . '积分';
                    break;
                case 4:
                    $v['name'] = $v['gift']['name'];
                    break;
                case 5:
                    $v['name'] = '谢谢参与';
                    break;
                default:
            }
        }
        unset($v);

        if ($list) {
            return [
                'code' => 0,
                'data' => $list,
            ];
        } else {
            return [
                'code' => 1,
                'data' => '',
            ];
        }
    }

    public function receive()
    {
        //检测
        $form = ScratchLog::findOne([
            'store_id' => $this->store_id,
            'user_id' => $this->user_id,
            'status' => 0,
            'id' => $this->id
        ]);
        if (empty($form)) {
            return [
                'code' => 1,
                'msg' => '网络异常',
            ];
        }

        $list = ScratchSetting::findOne(['store_id' => $this->store_id]);
        if ($list['start_time'] > time() || $list['end_time'] < time()) {
            return [
                'code' => 1,
                'msg' => '活动已结束或未开启',
            ];
        }

        if ($list->type == 1) {
            $start_time = strtotime(date('Y-m-d 00:00:00', time()));
            $end_time = $start_time + 86400;
        } elseif ($list->type == 2) {
            $start_time = $list['start_time'];
            $end_time = $list['end_time'];
        } else {
            return [
                'code' => 1,
                'msg' => '参数错误',
            ];
        }

        $log = ScratchLog::find()
            ->where(['store_id' => $this->store_id,'user_id' => $this->user_id])
            ->andWhere(['>','create_time',$start_time])
            ->andWhere(['<','create_time',$end_time])
            ->andWhere(['<>','status',0])
            ->count();
        if ($log >= $list['oppty']) {
            return [
                'code' => 1,
                'msg' => '机会已用完',
            ];
        }

        //扣积分
        if ($list->deplete_register > 0) {
            $num = (int)$list->deplete_register;

            $user = User::findOne(['id' => $this->user_id, 'store_id' => $this->store_id]);
            $register = new Register();
            $register->store_id = $this->store_id;
            $register->user_id = $this->user_id;
            $register->register_time = '..';
            $register->addtime = time();
            $register->continuation = 0;
            $register->type = 16;
            $register->integral = $num * -1;
            $register->save();

            $user->integral -= $num;
            if ($user->integral < 0) {
                return [
                    'code' => 1,
                    'msg' => '积分不足'
                ];
            }
            $t = \Yii::$app->db->beginTransaction();
            if (!$register->save()) {
                $t->rollBack();
                return $this->getErrorResponse($register);
            }
            if (!$user->save()) {
                $t->rollBack();
                return $this->getErrorResponse($user);
            } else {
                $t->commit();
            }
        }


        $form->status = 1;
        if ($form->save()) {
            //兑奖
            $award = $this->send($form);
 
            if ($award['code'] == 0) {
                $id = $award['data']['id'];  //中奖ID


                //tip
                if ($list['oppty'] - $log - 1 > 0) {
                //扣积分
                    if ($list['deplete_register'] > 0 && $user->integral < $list['deplete_register']) {
                        return [
                            'code' => 0,
                            'msg' => '积分不足',
                            'data' => [
                                'oppty' => $list['oppty'] - $log - 1,
                                'list' => [],
                            ]
                        ];
                    }

                    $sc = new ScratchForm();
                    $sc->store_id = $this->store_id;
                    $sc->user_id = $this->user_id;

                    return [
                    'code' => 0,
                    'msg' => '成功',
                    'data' => [
                        'oppty' => $list['oppty'] - $log - 1,
                        'list' => $sc->lottery($list, $log - 1)['data']['list'],
                    ]
                    ];
                } else {
                    return [
                    'code' => 0,
                    'msg' => '机会已用完',
                    'data' => [
                        'oppty' => $list['oppty'] - $log - 1,
                        'list' => [],
                    ]
                    ];
                }
            } else {
                return [
                    'code' => 1,
                    'msg' => $award['msg'],
                ];
            }
        } else {
            return $this->getErrorResponse($form);
        }
    }



    protected function send($prize)
    {
        if ($prize->status != 1) {
            return;
        }

        //余额
        if ($prize->type == 1) {
            if ($prize->price <= 0) {
                return;
            }
            //日志
            $log = new UserAccountLog();
            $log->user_id = $this->user_id;
            $log->type = 1;
            $log->price = floatval($prize->price);
            $log->addtime = time();
            $log->order_type = 9;
            $log->desc = '刮刮卡抽奖：' . $prize->id;
            $log->order_id = $prize->id;
            $log->save();
            
            //状态
            $prize->status = 2;
            $prize->raffle_time = time();

            //充值
            $user = User::find()->where(['store_id' => $this->store_id,'id' => $this->user_id])->one();
            $user->money = $user->money + floatval($prize->price);

            $t = \Yii::$app->db->beginTransaction();
            if (!$prize->save()) {
                $t->rollBack();
                return $this->getErrorResponse($prize);
            }
            if (!$user->save()) {
                $t->rollBack();
                return $this->getErrorResponse($user);
            } else {
                $t->commit();
                return [
                    'code' => 0,
                    'msg' => '领取成功',
                    'data' => [
                        'id' => $prize->id
                    ]
                ];
            }
        }
        if ($prize->type == 2) {
            $coupon = Coupon::find()->where(['store_id' => $this->store_id,'is_delete' => 0,'id' => $prize->coupon_id])->one();
            if (!$coupon) {
                return [
                    'code' => 1,
                    'msg' => '网络异常'
                ];
            }

            // $user_coupon = UserCoupon::find()->where(['store_id'=>$this->store_id,'coupon_id'=>$coupon['id'],'user_id'=>$this->user_id,'type'=>2,'is_delete'=>0])->exists();
            // if($user_coupon){
            //     return [
            //         'code'=>1,
            //         'msg'=>'重复领取',
            //         'data'=>[]
            //     ];
            // }
            // $coupon_count = UserCoupon::find()->where(['store_id'=>$this->store_id,'is_delete'=>0,'coupon_id'=>$coupon['id'],'type'=>2])->count();
           
            // if($coupon['total_count'] != -1 && $coupon['total_count'] <= $coupon_count){
            //     return [
            //         'code'=>1,
            //         'msg'=>'优惠券已领完'
            //     ];
            // }

            //状态
            $prize->status = 2;
            $prize->raffle_time = time();

            //增加
            $res = new UserCoupon();
            $res->user_id = $this->user_id;
            $res->store_id = $this->store_id;
            $res->coupon_id = $prize->coupon_id;
            $res->coupon_auto_send_id = 0;
            $res->type = 2;
            $res->is_use = 0;
            $res->is_expire = 0;
            $res->is_delete = 0;
            $res->addtime = time();
            if ($coupon['expire_type'] == 1) {
                $res->begin_time = time();
                $res->end_time = time() + max(0, 86400 * $coupon['expire_day']);
            } elseif ($coupon['expire_type'] == 2) {
                $res->begin_time = $coupon['begin_time'];
                $res->end_time = $coupon['end_time'];
            }


            $t = \Yii::$app->db->beginTransaction();
            if (!$prize->save()) {
                $t->rollBack();
                return $this->getErrorResponse($prize);
            }
            if (!$res->save()) {
                $t->rollBack();
                return $this->getErrorResponse($user);
            } else {
                $t->commit();
                return [
                    'code' => 0,
                    'msg' => '领取成功',
                    'data' => [
                        'id' => $prize->id
                    ],
                ];
            }
        }
        if ($prize->type == 3) {
            $user = User::findOne(['id' => $this->user_id, 'store_id' => $this->store_id]);
            
            if (!$user) {
                return [
                    'code' => 1,
                    'msg' => '用户不存在，或已删除',
                ];
            }

            $register = new Register();
            $register->store_id = $this->store_id;
            $register->user_id = $this->user_id;
            $register->register_time = '..';
            $register->addtime = time();
            $register->continuation = 0;
            $register->type = 16;
            $register->integral = $prize->num;
            $register->save();

            //状态
            $prize->status = 2;
            $prize->raffle_time = time();

            $user->integral += $prize->num;
            $user->total_integral += $prize->num;

            $t = \Yii::$app->db->beginTransaction();
            if (!$prize->save()) {
                $t->rollBack();
                return $this->getErrorResponse($prize);
            }
            if (!$user->save()) {
                $t->rollBack();
                return $this->getErrorResponse($user);
            } else {
                $t->commit();
                return [
                    'code' => 0,
                    'msg' => '领取成功',
                    'data' => [
                        'id' => $prize->id
                    ]
                ];
            }
        }
        if ($prize->type == 4) {
            return [
                'code' => 0,
                'msg' => '领取成功',
                'data' => [
                    'id' => $prize->id
                ]
            ];
        }
        if ($prize->type == 5) {
            $prize->status = 2;
            $prize->raffle_time = time();
            $prize->save();
            return [
                'code' => 0,
                'msg' => '领取成功',
                'data' => [
                    'id' => $prize->id
                ]
            ];
        }
    }
}
