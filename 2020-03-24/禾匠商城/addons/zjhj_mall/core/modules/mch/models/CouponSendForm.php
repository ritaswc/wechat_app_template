<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/24
 * Time: 17:27
 */

namespace app\modules\mch\models;

use app\models\ActivityMsgTpl;
use app\models\Coupon;
use app\models\User;
use app\models\WechatTplMsgSender;

class CouponSendForm extends MchModel
{
    public $user_id_list;
    public $store_id;
    public $coupon_id;

    public function rules()
    {
        return [
            [['user_id_list'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id_list' => '发放对象',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $coupon = Coupon::findOne([
            'id' => $this->coupon_id,
            'is_delete' => 0,
            'store_id' => $this->store_id,
        ]);
        if (!$coupon) {
            return [
                'code' => 1,
                'msg' => '优惠券不存在',
            ];
        }
        $user_list = User::find()->select('id')->where(['id' => $this->user_id_list, 'store_id' => $this->store_id])->all();
        $count = 0;
        foreach ($user_list as $u) {
            $res = Coupon::userAddCoupon($u->id, $this->coupon_id);
            if ($res) {
                $count++;
            }

            $msgTpl = new ActivityMsgTpl($u->id, 'COUPON');
            $msgTpl->activitySuccessMsg('优惠券发放', $coupon->name, '您有新的优惠券待查收！');
        }
        return [
            'code' => 0,
            'msg' => "操作完成，共发放{$count}人次。",
        ];
    }
}
