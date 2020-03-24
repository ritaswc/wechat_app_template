<?php
namespace app\modules\api\models\pond;

use app\modules\api\models\ApiModel;
use app\models\PondLog;
use yii\data\Pagination;
use app\models\UserAccountLog;
use app\models\User;
use app\models\Coupon;
use app\models\UserCoupon;
use app\models\CouponAutoSend;
use app\modules\api\models\CouponListForm;
use app\models\IntegralLog;
use app\models\Register;

class PondLogForm extends ApiModel
{
    public $user_id;
    public $store_id;
    public $page;
    public $limit;
    public $id;

    public function search()
    {

        $form = PondLog::find()
            ->where([
                'store_id' => $this->store_id,
                'user_id' => $this->user_id
            ])->andWhere(['<>','type','5'])
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
            $v['type'] = (int)$v['type'];
            $v['create_time'] = date('Y:m:d H:i:s', $v['create_time']);
            switch ($v['type']) {
                case 1:
                    $v['name'] = $v['price'].'元余额';
                    break;
                case 2:
                    $v['name'] = $v['coupon']['name'];
                    break;
                case 3:
                    $v['name'] = $v['num'].'积分';
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

    public function send()
    {
        $prize = PondLog::findOne([
            'user_id' => $this->user_id,
            'store_id' => $this->store_id,
            'status' => 0,
            'id' => $this->id
        ]);
        if (empty($prize)) {
            return [
                'code' => 1,
                'msg' => '网络异常'
            ];
        }
        //余额
        if ($prize->type==1) {
            if (floatval($prize->price) <=0) {
                return;
            }
            //日志
            $log = new UserAccountLog();
            $log->user_id = $this->user_id;
            $log->type = 1;
            $log->price = floatval($prize->price);
            $log->addtime = time();
            $log->order_type = 8;
            $log->desc = '大转盘抽奖：'.$prize->id;
            $log->order_id = $prize->id;
            $log->save();
            
            //状态
            $pondlog = PondLog::find()->where(['store_id'=>$this->store_id,'id'=>$this->id,'status' => 0])->one();
            $pondlog->status = 1;
            $pondlog->raffle_time = time();

            //充值
            $user = User::find()->where(['store_id'=>$this->store_id,'id'=>$this->user_id])->one();
            $user->money = $user->money + floatval($prize->price);

            $t = \Yii::$app->db->beginTransaction();
            if (!$pondlog->save()) {
                $t->rollBack();
                return $this->getErrorResponse($pondlog);
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
        if ($prize->type==2) {
            $coupon = Coupon::find()->where(['store_id'=>$this->store_id,'is_delete'=>0,'id'=>$prize->coupon_id])->one();
            if (!$coupon) {
                return [
                    'code'=>1,
                    'msg'=>'网络异常'
                ];
            }

            // $user_coupon = UserCoupon::find()->where(['store_id'=>$this->store_id,'coupon_id'=>$coupon['id'],'user_id'=>$this->user_id,'type'=>2,'is_delete'=>0])->exists();
            // if ($user_coupon) {
            //     return [
            //         'code'=>1,
            //         'msg'=>'重复领取',
            //         'data'=>[]
            //     ];
            // }
            // $coupon_count = UserCoupon::find()->where(['store_id'=>$this->store_id,'is_delete'=>0,'coupon_id'=>$coupon['id'],'type'=>2])->count();
           
            // if ($coupon['total_count'] != -1 && $coupon['total_count'] <= $coupon_count) {
            //     return [
            //         'code'=>1,
            //         'msg'=>'优惠券已领完'
            //     ];
            // }

            //状态
            $pondlog = PondLog::find()->where(['store_id'=>$this->store_id,'id'=>$this->id,'status' => 0])->one();
            $pondlog->status = 1;
            $pondlog->raffle_time = time();

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
            if (!$pondlog->save()) {
                $t->rollBack();
                return $this->getErrorResponse($pondlog);
            }
            if (!$res->save()) {
                $t->rollBack();
                return $this->getErrorResponse($user);
            } else {
                $t->commit();
                return [
                    'code'=>0,
                    'msg'=>'领取成功',
                    'data' => [
                        'id' => $prize->id
                    ],
                ];
            }
        }
        if ($prize->type==3) {
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
            $register->type = 15;
            $register->integral = $prize->num;
            $register->save();

            //状态
            $pondlog = PondLog::find()->where(['store_id'=>$this->store_id,'id'=>$this->id,'status' => 0])->one();
            $pondlog->status = 1;
            $pondlog->raffle_time = time();

            $user->integral += $prize->num;
            $user->total_integral += $prize->num;

            $t = \Yii::$app->db->beginTransaction();
            if (!$pondlog->save()) {
                $t->rollBack();
                return $this->getErrorResponse($pondlog);
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
    }
}
