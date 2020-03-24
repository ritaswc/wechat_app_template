<?php

namespace app\models;

use app\models\common\admin\log\CommonActionLog;
use Yii;

/**
 * This is the model class for table "{{%coupon}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $name
 * @property string $desc
 * @property string $pic_url
 * @property integer $discount_type
 * @property string $min_price
 * @property string $sub_price
 * @property string $discount
 * @property integer $expire_type
 * @property integer $expire_day
 * @property integer $begin_time
 * @property integer $end_time
 * @property integer $addtime
 * @property integer $is_delete
 * @property integer $total_count
 * @property integer $is_join
 * @property integer $sort
 * @property integer $cat_id_list
 * @property integer $appoint_type
 * @property integer $goods_id_list
 * @property integer $is_integral
 * @property integer $integral
 * @property integer $total_num
 * @property integer $user_num
 * @property integer $price
 * @property string $rule
 */
class Coupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $num;
    public $type;
    public static function tableName()
    {
        return '{{%coupon}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'name'], 'required'],
            [['store_id', 'discount_type', 'expire_type', 'expire_day', 'begin_time', 'end_time', 'addtime', 'type','is_delete', 'total_count', 'is_join', 'sort','appoint_type','is_integral','integral','total_num','user_num','num'], 'integer'],
            [['min_price', 'sub_price', 'discount','price'], 'number'],
            [['name','cat_id_list','goods_id_list'], 'string', 'max' => 255],
            [['desc', 'pic_url'], 'string', 'max' => 2000],
            [['begin_time', 'end_time'], 'integer', 'max' => 2000000000],
            [['rule'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'name' => '优惠券名称',
            'desc' => 'Desc',
            'pic_url' => 'Pic Url',
            'discount_type' => '优惠券类型：1=折扣，2=满减',
            'min_price' => '最低消费金额',
            'sub_price' => '优惠金额',
            'discount' => '折扣率',
            'expire_type' => '到期类型：1=领取后N天过期，2=指定有效期',
            'expire_day' => '有效天数，expire_type=1时',
            'begin_time' => '有效期开始时间',
            'end_time' => '有效期结束时间',
            'addtime' => 'Addtime',
            'is_delete' => 'Is Delete',
            'total_count' => '发放总数量',
            'is_join' => '是否加入领券中心 1--不加入领券中心 2--加入领券中心',
            'sort' => '排序按升序排列',
            'cat_id_list' => '商品分类id',
            'appoint_type' => '指定类别或商品',
            'goods_id_list' => '指定商品id',
            'is_integral' => '是否加入积分商城 ',
            'integral' => '购买需要积分数量',
            'total_num' => '积分商城发放总数',
            'user_num' => '每人限制兑换数量',
            'price' => '售价',
            'rule' => '使用规则说明',
        ];
    }

    public function getBeginTime()
    {
        return date('Y-m-d H:i', $this->begin_time);
    }
    public function getEndTime()
    {
        return date('Y-m-d H:i', $this->end_time);
    }

    /**
     * 给用户发放优惠券
     * @param integer $user_id 用户id
     * @param integer $coupon_id 优惠券id
     * @param integer $coupon_auto_send_id 自动发放id
     * @param integer $type 领券类型
     * @return boolean
     */
    public static function userAddCoupon($user_id, $coupon_id, $coupon_auto_send_id = 0, $type = 0)
    {
        $user = User::findOne($user_id);
        if (!$user) {
            return false;
        }
        $coupon = Coupon::findOne([
            'id' => $coupon_id,
            'is_delete' => 0,
        ]);
        if (!$coupon) {
            return false;
        }
        if ($coupon->total_count == 0) {
            return false;
        }
        $user_coupon = new UserCoupon();
        if ($type == 2) {
            $res = UserCoupon::find()->where(['is_delete'=>0,'type'=>2,'user_id'=>$user_id,'coupon_id'=>$coupon_id])->exists();
            if ($res) {
                return false;
            }
        }
        if ($coupon_auto_send_id) {
            $coupon_auto_send = CouponAutoSend::findOne([
                'id' => $coupon_auto_send_id,
                'is_delete' => 0,
            ]);
            if (!$coupon_auto_send) {
                return false;
            }
            if ($coupon_auto_send->send_times != 0) {
                $send_count = UserCoupon::find()->where([
                    'coupon_auto_send_id' => $coupon_auto_send->id,
                    'user_id' => $user->id,
                ])->count();
                if ($send_count && $send_count >= $coupon_auto_send->send_times) {
                    return false;
                }
            }
            $user_coupon->coupon_auto_send_id = $coupon_auto_send->id;
            $type = 1;
        }
        $user_coupon->type = $type;
        $user_coupon->store_id = $user->store_id;
        $user_coupon->user_id = $user->id;
        $user_coupon->coupon_id = $coupon->id;
        if ($coupon->expire_type == 1) {
            $user_coupon->begin_time = time();
            $user_coupon->end_time = time() + max(0, 86400 * $coupon->expire_day);
        } elseif ($coupon->expire_type == 2) {
            $user_coupon->begin_time = $coupon->begin_time;
            $user_coupon->end_time = $coupon->end_time;
        }
        $user_coupon->is_expire = 0;
        $user_coupon->is_use = 0;
        $user_coupon->is_delete = 0;
        $user_coupon->addtime = time();
        return $user_coupon->save();
    }
    public function getCount()
    {
        return UserCoupon::find()->where(['coupon_id'=>$this->id,'is_delete'=>0,'type'=>2])->count();
    }

    public function afterSave($insert, $changedAttributes)
    {
        $data = $insert ? json_encode($this->attributes) : json_encode($changedAttributes);
        CommonActionLog::storeActionLog('', $insert, $this->is_delete, $data, $this->id);
    }
}
