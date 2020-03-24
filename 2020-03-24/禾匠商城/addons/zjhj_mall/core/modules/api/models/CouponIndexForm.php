<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/25
 * Time: 14:39
 */

namespace app\modules\api\models;

use app\models\Coupon;
use app\models\CouponAutoSend;
use app\models\UserCoupon;
use app\models\Cat;

class CouponIndexForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $status;
    public $id;
    public $coupon_id;

    public function rules()
    {
        return [
            [['status','id','coupon_id'], 'integer'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = UserCoupon::find()->alias('uc')->leftJoin(['c' => Coupon::tableName()], 'uc.coupon_id=c.id')->leftJoin(['cas' => CouponAutoSend::tableName()], 'cas.id=uc.coupon_auto_send_id')
            ->where(['uc.user_id' => $this->user_id]);
        if ($this->status == 0) {
            $query->andWhere([
                'uc.is_delete' => 0,
                'uc.is_use' => 0,
                'uc.is_expire' => 0,
            ]);
        }
        if ($this->status == 1) {
            $query->andWhere([
                'uc.is_delete' => 0,
                'uc.is_use' => 1,
                'uc.is_expire' => 0,
            ]);
        }
        if ($this->status == 2) {
            $query->andWhere([
                'uc.is_delete' => 0,
                'uc.is_use' => 0,
                'uc.is_expire' => 1,
            ]);
        }
        $list = $query->orderBy('uc.addtime DESC')
            ->limit(200)
            ->select('uc.id user_coupon_id,c.sub_price,c.min_price,uc.begin_time,uc.end_time,uc.is_use,uc.is_expire,cas.event,uc.type,c.appoint_type,c.cat_id_list,c.goods_id_list')->asArray()->all();

        $events = [
            0 => '平台发放',
            1 => '分享红包',
            2 => '购物返券',
            3 => '领券中心',
            4 => '积分兑换',
        ];
        foreach ($list as $i => $item) {
            $list[$i]['status'] = 0;
            if ($item['is_use']) {
                $list[$i]['status'] = 1;
            }
            if ($item['is_expire']) {
                $list[$i]['status'] = 2;
            }
            $list[$i]['min_price_desc'] = $item['min_price'] == 0 ? '无门槛' : '满' . $item['min_price'] . '元可用';
            $list[$i]['begin_time'] = date('Y.m.d H:i', $item['begin_time']);
            $list[$i]['end_time'] = date('Y.m.d H:i', $item['end_time']);
            if (!$item['event']) {
                if ($item['type'] == 2) {
                    $list[$i]['event'] = $item['event'] = 3;
                } elseif ($item['type'] == 0) {
                    $list[$i]['event'] = $item['event'] = 0;
                } else {
                    $list[$i]['event'] = $item['event'] = 4;
                }
            }
            $list[$i]['event_desc'] = $events[$item['event']];

            if ($list[$i]['appoint_type'] == 1) {
                $list[$i]['cat'] = Cat::find()->select('name')->where(['store_id'=>$this->store_id,'is_delete'=>0,'id'=>json_decode($item['cat_id_list'])])->asArray()->all();
                $list[$i]['goods'] = [];
            } elseif ($list[$i]['appoint_type'] == 2) {
                $list[$i]['goods'] = json_decode($list[$i]['goods_id_list']);
                $list[$i]['cat'] = [];
            } else {
                $list[$i]['goods'] = [];
                $list[$i]['cat'] = [];
            }
        }
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'list' => $list,
            ],
        ];
    }
    public function detail()
    {
        if ($this->coupon_id) {
            $list = Coupon::find()->alias('c')
                ->where([
                    'c.is_delete' => 0,
                    'c.is_join' => 2,
                    'c.store_id' => $this->store_id,
                    'c.id' => $this->coupon_id,
                ])
                ->leftJoin(UserCoupon::tableName() . ' uc', "uc.coupon_id=c.id and uc.user_id ={$this->user_id} and uc.type = 2 and uc.is_delete=0")
                ->select(['c.*', '(case when isnull(uc.id) then 0 else 1 end) as is_receive'])
                ->asArray()
                ->one();

            $coupon = $list;
        };

        if ($this->id) {
            $list = UserCoupon::find()
                ->where([
                    'store_id' => $this->store_id,
                    'is_delete' => 0,
                    'id' => $this->id,
                    'user_id' => $this->user_id,
                ])->with('coupon')->asArray()->one();

            if ($coupon = $list['coupon']) {
                $list['min_price'] = $coupon['min_price'];
                $list['sub_price'] = $coupon['sub_price'];
                $list['appoint_type'] = $coupon['appoint_type'];
                $list['rule'] = $coupon['rule'];
                $list['expire_type'] = 2;

                $list['status'] = 0;
                if ($list['is_use']) {
                    $list['status'] = 1;
                }
                if ($list['is_expire']) {
                    $list['status'] = 2;
                }
            }
        }
 
    
        if ($list) {
            if ($coupon['sub_price'] >= 100) {
                $list['sub_price'] = (int)$coupon['sub_price'];
            }
            if ($coupon['min_price'] >= 100) {
                $list['min_price'] = (int)$coupon['min_price'];
            }
            $list['min_price_desc'] = $coupon['min_price'] == 0 ? '无门槛' : '满' . $coupon['min_price'] . '元可用';
            $list['begin_time'] = date('Y.m.d H:i', $list['begin_time']);
            $list['end_time'] = date('Y.m.d H:i', $list['end_time']);

            if ($list['appoint_type'] == 1) {
                $list['cat'] = Cat::find()->select('id,name')->where(['store_id'=>$this->store_id,'is_delete'=>0,'id'=>json_decode($coupon['cat_id_list'])])->asArray()->all();
                $list['goods'] = [];
            } elseif ($list['appoint_type'] == 2) {
                $list['goods'] = json_decode($coupon['goods_id_list']);
                $list['cat'] = [];
            } else {
                $list['goods'] = [];
                $list['cat'] = [];
            }

            return [
                'code' => 0,
                'msg' => 'success',
                'data' => [
                    'list' => $list,
                ],
            ];
        } else {
            return [
                'code' => 0,
                'msg' => '不存在',
                'data' => [
                    'list' => [],
                ],
            ];
        }
    }
}
