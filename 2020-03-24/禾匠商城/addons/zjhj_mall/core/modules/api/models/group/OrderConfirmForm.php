<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/20
 * Time: 10:25
 */

namespace app\modules\api\models\group;

use app\models\common\api\CommonShoppingList;
use app\utils\PinterOrder;
use app\utils\PrinterPtOrder;
use app\models\Order;
use app\models\PrinterSetting;
use app\models\PtOrder;
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
        $order = PtOrder::findOne([
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


        if ($order->save()) {
            $printer_order = new PinterOrder($this->store_id, 'confirm', $order->id, 2);
            $res = $printer_order->print_order();
            $wechatAccessToken = $this->getWechat()->getAccessToken();
            $res = CommonShoppingList::updateBuyGood($wechatAccessToken, $order, 2, 100);
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
