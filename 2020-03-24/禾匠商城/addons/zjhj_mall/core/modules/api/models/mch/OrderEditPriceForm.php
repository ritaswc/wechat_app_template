<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 *
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/4/19
 * Time: 16:01
 */


namespace app\modules\api\models\mch;

use app\models\Order;
use app\models\OrderDetail;
use app\modules\api\models\ApiModel;

class OrderEditPriceForm extends ApiModel
{
    public $mch_id;
    public $order_id;
    public $type;
    public $price;

    public function rules()
    {
        return [
            [['order_id', 'type', 'price'], 'required'],
            [['price'], 'number', 'min' => '0.01'],
            [['type'], 'in', 'range' => ['sub', 'add']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'price' => '金额',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $order = Order::findOne([
            'id' => $this->order_id,
            'mch_id' => $this->mch_id,
        ]);
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在。',
            ];
        }
        if ($order->is_pay == 1) {
            return [
                'code' => 0,
                'msg' => '订单已付款，不能再修改价格。',
            ];
        }
        if ($this->type == 'sub') {
            $max_sub_price = $order->pay_price - $order->express_price - 0.01;
            if ($this->price > $max_sub_price) {
                return [
                    'code' => 1,
                    'msg' => '优惠金额不能大于' . $max_sub_price . '元。',
                ];
            }
        }
        /** @var OrderDetail[] $order_detail_list */
        $order_detail_list = OrderDetail::find()->where(['order_id' => $order->id, 'is_delete' => 0])->all();
        $order_detail_total_price = 0;
        $order_detail_price_list = [];
        foreach ($order_detail_list as $item) {
            $order_detail_total_price += $item->total_price;
        }
        if ($this->type == 'sub') {
            foreach ($order_detail_list as $item) {
                $item->total_price = $item->total_price - number_format($item->total_price * $this->price / $order_detail_total_price, 2, '.', '');
                $item->save();
            }
            $order->before_update_price = $order->pay_price;
            $order->pay_price = $order->pay_price - $this->price;
            $order->save();
        } else {
            foreach ($order_detail_list as $item) {
                $item->total_price = $item->total_price + number_format($item->total_price * $this->price / $order_detail_total_price, 2, '.', '');
                $item->save();
            }
            $order->before_update_price = $order->pay_price;
            $order->pay_price = $order->pay_price + $this->price;
            $order->save();
        }
        return [
            'code' => 0,
            'msg' => '价格修改成功。',
        ];
    }
}
