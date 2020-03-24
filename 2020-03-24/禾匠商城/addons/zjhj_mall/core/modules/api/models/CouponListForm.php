<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/21
 * Time: 11:06
 */

namespace app\modules\api\models;

use app\models\Cat;
use app\models\Coupon;
use app\models\IntegralCouponOrder;
use app\models\UserCoupon;
use app\models\Goods;

class CouponListForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $id;

    public function getList()
    {
        if (!$this->user_id) {
            $this->user_id = 0;
        }
        $coupon_list = Coupon::find()->alias('c')->where([
            'c.is_delete' => 0, 'c.is_join' => 2, 'c.store_id' => $this->store_id
        ])
            ->andWhere(['!=', 'c.total_count', 0])
            ->leftJoin(UserCoupon::tableName() . ' uc', "uc.coupon_id=c.id and uc.user_id ={$this->user_id} and uc.type = 2 and uc.is_delete=0")->select([
                'c.*', '(case when isnull(uc.id) then 0 else 1 end) as is_receive'
            ])
            ->orderBy('is_receive ASC,sort ASC,id DESC')->asArray()->all();
        $new_list = [];
        foreach ($coupon_list as $index => $value) {
            if ($value['min_price'] >= 100) {
                $coupon_list[$index]['min_price'] = (int)$value['min_price'];
            }
            if ($value['sub_price'] >= 100) {
                $coupon_list[$index]['sub_price'] = (int)$value['sub_price'];
            }
            $coupon_list[$index]['begintime'] = date('Y.m.d', $value['begin_time']);
            $coupon_list[$index]['endtime'] = date('Y.m.d', $value['end_time']);
            $coupon_list[$index]['content'] = "适用范围：全场通用";
            if ($value['appoint_type'] == 1 && $value['cat_id_list'] !== 'null') {
                $coupon_list[$index]['cat'] = Cat::find()->select('id,name')->where(['store_id'=>$this->store_id,'is_delete'=>0,'id'=>json_decode($value['cat_id_list'])])->asArray()->all();
                $cat_list = [];
                foreach ($coupon_list[$index]['cat'] as $item) {
                    $cat_list[] = $item['name'];
                }
                $coupon_list[$index]['content'] = "适用范围：仅限分类：".implode('、', $cat_list)."使用";
                $coupon_list[$index]['goods'] = [];
            } elseif ($value['appoint_type'] == 2 && $value['goods_id_list'] !== 'null') {
                $coupon_list[$index]['goods'] = Goods::find()->select('id')->where(['store_id'=>$this->store_id,'is_delete'=>0,'id'=>json_decode($value['goods_id_list'])])->asArray()->all();
                $coupon_list[$index]['cat'] = [];
                $coupon_list[$index]['content'] = "指定商品使用 点击查看指定商品";
            } else {
                $coupon_list[$index]['goods'] = [];
                $coupon_list[$index]['cat'] = [];
            }
            if($value['is_receive'] == 0){
                $coupon_list[$index]['receive_content'] = '立即领取';
            }else{
                $coupon_list[$index]['receive_content'] = '已领取';
            }

            $coupon_count = UserCoupon::find()->where([
                'store_id'=>$this->store_id,'is_delete'=>0,'coupon_id'=>$value['id'],'type'=>2
            ])->count();
            if ($value['total_count'] > $coupon_count || $value['total_count'] == -1) {
                if ($value['expire_type'] == 2) {
                    if ($value['end_time'] >= time()) {
                        $new_list[] = $coupon_list[$index];
                    }
                } else {
                    $new_list[] = $coupon_list[$index];
                }
            }
        }
        return $new_list;
    }

    public function couponInfo()
    {
        $coupon = Coupon::find()->where(['id'=>$this->id])->one();
        $coupon->begin_time = $coupon->beginTime;
        $coupon->end_time = $coupon->endTime;
        switch ($coupon->appoint_type) {
            case 1:
                $info = Cat::find()->where(['id' => json_decode($coupon->cat_id_list)]);
                break;
            case 2:
                $info = Goods::find()->where(['id' => json_decode($coupon->goods_id_list)]);
                break;
            default:
                $info = null;
        }
        if ($info) {
            $info = $info->select('id,name')->andWhere(['is_delete' => 0, 'store_id' => $this->store_id])->asArray()->all();
        }
        $count = IntegralCouponOrder::find()->where(['user_id'=>$this->user_id,'is_pay'=>1,'store_id'=>$this->store_id,'coupon_id' => $coupon->id])->count();
        if ($count >= $coupon->user_num) {
            $coupon->type = 1;
            $coupon->num = $count;
        } else {
            $coupon->num = $count;
            $coupon->type = 0;
        }
        return [
            'code' => 0,
            'data' => [
                'coupon'=>$coupon,
                'info'=>$info,
            ],
        ];
    }
}
