<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/20
 * Time: 10:25
 */

namespace app\modules\api\models\miaosha;

use app\models\common\api\CommonShoppingList;
use app\utils\PinterOrder;
use app\models\Level;
use app\models\MsOrder;
use app\models\Order;
use app\models\PrinterSetting;
use app\models\User;
use app\modules\api\models\ApiModel;
use app\utils\TaskCreate;

class OrderConfirmForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $order_id;

    public function rules()
    {
        return [
            [['order_id'], 'required'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $order = MsOrder::findOne([
            'store_id' => $this->store_id,
            'user_id' => $this->user_id,
            'id' => $this->order_id,
            'is_send' => 1,
            'is_delete' => 0,
        ]);
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在'
            ];
        }
        if ($order->pay_type == 2) {
            $order->is_pay = 1;
            $order->pay_time = time();
        }
        $order->is_confirm = 1;
        $order->confirm_time = time();
        /*
                $user = User::findOne(['id' => $order->user_id, 'store_id' => $this->store_id]);
                $order_money = Order::find()->where(['store_id' => $this->store_id, 'user_id' => $user->id, 'is_delete' => 0])
                    ->andWhere(['is_pay' => 1, 'is_confirm' => 1])->select([
                        'sum(pay_price)'
                    ])->scalar();
                $next_level = Level::find()->where(['store_id' => $this->store_id, 'is_delete' => 0,'status'=>1])
                    ->andWhere(['<', 'money', $order_money])->orderBy(['level' => SORT_DESC])->asArray()->one();
                if ($user->level < $next_level['level']) {
                    $user->level = $next_level['level'];
                    $user->save();
                }
        */

        if ($order->save()) {
            $printer_order = new PinterOrder($this->store_id, $order->id, 'confirm', 1);
            $res = $printer_order->print_order();

            $wechatAccessToken = $this->getWechat()->getAccessToken();
            $res = CommonShoppingList::updateBuyGood($wechatAccessToken, $order, 1, 100);

            return [
                'code' => 0,
                'msg' => '已确认收货'
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '确认收货失败'
            ];
        }
    }
}
