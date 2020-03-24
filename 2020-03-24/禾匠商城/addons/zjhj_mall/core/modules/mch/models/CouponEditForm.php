<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/24
 * Time: 11:59
 */

namespace app\modules\mch\models;

use app\models\Coupon;
use app\models\UserCoupon;

/**
 * @property Coupon $coupon
 */
class CouponEditForm extends MchModel
{
    public $store_id;
    public $coupon;

    public $name;
    public $discount_type;
    public $min_price;
    public $sub_price;
    public $discount;
    public $expire_type;
    public $expire_day;
    public $begin_time;
    public $end_time;
    public $total_count;
    public $is_join;
    public $sort;
    public $cat_id_list;
    public $appoint_type;
    public $goods_id_list;
    public $rule;

    public function rules()
    {
        return [
            [['name'], 'trim'],
            [['name', 'discount_type', 'min_price', 'sub_price', 'discount', 'expire_type', 'expire_day', 'begin_time', 'end_time'], 'required'],
            [['sort'], 'integer', 'min' => 0, 'max' => 999999],
            [['expire_day'], 'integer', 'min' => 0, 'max' => 999],
            [['min_price', 'sub_price'], 'number', 'min' => 0, 'max' => 999999],
            [['discount',], 'number', 'min' => 0.1, 'max' => 10],
            [['total_count'], 'number', 'min' => -1],
            [['total_count'], 'default', 'value' => -1],
            [['is_join'], 'in', 'range' => [1, 2]],
            [['sort'], 'default', 'value' => 100],
            [['cat_id_list', 'goods_id_list'], 'safe'],
            [['appoint_type'], 'integer', 'min' => 0],
            [['rule'], 'string', 'max' => 1000],
        ];
    }

    public function attributeLabels()
    { 
        return [
            'name' => '优惠券名称',
            'discount_type' => '优惠券类型',
            'min_price' => '最低消费金额',
            'sub_price' => '优惠金额',
            'discount' => '折扣率',
            'expire_type' => '到期类型',
            'expire_day' => '有效天数',
            'begin_time' => '有效期开始时间',
            'end_time' => '有效期结束时间',
            'total_count' => '发放总数量',
            'is_join' => '加入领券中心',
            'sort' => '排序',
            'cat_id_list' => '商品分类id',
            'appoint_type' => '指定类别或商品',
            'goods_id_list' => '指定商品id',
            'rule' => '使用说明',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $this->coupon->name = $this->name;
        $this->coupon->discount_type = $this->discount_type;
        $this->coupon->min_price = $this->min_price;
        $this->coupon->sub_price = $this->sub_price;
        $this->coupon->discount = $this->discount;
        $this->coupon->expire_type = $this->expire_type;
        $this->coupon->expire_day = $this->expire_day;
        $this->coupon->begin_time = strtotime($this->begin_time . ' 00:00:00');
        $this->coupon->end_time = strtotime($this->end_time . ' 23:59:59');
        $this->coupon->total_count = $this->total_count;
        $this->coupon->is_join = $this->is_join;
        $this->coupon->sort = $this->sort;
        $this->coupon->rule = $this->rule;
        $this->coupon->appoint_type = $this->appoint_type;
        $old_cat_id_list = json_decode($this->coupon->cat_id_list);

        if($this->coupon->begin_time>2000000000 || $this->coupon->end_time>2000000000){
            return [
                'code' => 1,
                'msg' => '有效期范围超过限制'
            ];
        }
        if (count($old_cat_id_list) < 1) {
            $this->coupon->cat_id_list = \Yii::$app->serializer->encode($this->cat_id_list);
        } else {
            if ($this->cat_id_list) {
                $new_cat_id_list = array_merge($old_cat_id_list, $this->cat_id_list);
                $this->coupon->cat_id_list = \Yii::$app->serializer->encode($new_cat_id_list);
            }
        }
        $old_goods_id_list = json_decode($this->coupon->goods_id_list);
        if (count($old_goods_id_list) < 1) {
            $this->coupon->goods_id_list = \Yii::$app->serializer->encode($this->goods_id_list);
        } else {
            if ($this->goods_id_list) {
                $new_goods_id_list = array_merge($old_goods_id_list, $this->goods_id_list);
                $this->coupon->goods_id_list = \Yii::$app->serializer->encode($new_goods_id_list);
            }
        }

        if ($this->coupon->isNewRecord) {
            $this->coupon->store_id = $this->store_id;
            $this->coupon->addtime = time();
        } else {
            $coupon_count = UserCoupon::find()->where(['store_id' => $this->store_id, 'is_delete' => 0, 'coupon_id' => $this->coupon->id, 'type' => 2])->count();
            if ($coupon_count > $this->total_count && $this->total_count != -1) {
                return [
                    'code' => 1,
                    'msg' => '优惠券总数不得小于已领取总数'
                ];
            }
        }
        if ($this->coupon->save()) {
            return [
                'code' => 0,
                'msg' => '保存成功',
            ];
        } else {
            return $this->getErrorResponse($this->coupon);
        }
    }
}
