<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 *
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/4/17
 * Time: 15:05
 */


namespace app\modules\api\models\mch;


use app\models\Express;
use app\models\ExpressDetailForm;
use app\models\Goods;
use app\models\Order;
use app\models\OrderDetail;
use app\modules\api\models\ApiModel;

class OrderDetailForm extends ApiModel
{
    public $id;
    public $mch_id;

    public function rules()
    {
        return [
            ['id', 'required'],
        ];
    }

    public function search()
    {
        if (!$this->validate())
            return $this->errorResponse;
        $order = Order::findOne([
            'id' => $this->id,
            'mch_id' => $this->mch_id,
        ]);
        if (!$order)
            return [
                'code' => 1,
                'msg' => '订单不存在。',
            ];
        $status_text = '';
        if ($order->is_pay == 0) {
            $status_text = '待付款';
        }
        if ($order->is_pay == 1) {
            $status_text = '待发货';
        }
        if ($order->is_send == 1) {
            $status_text = '待收货';
        }
        if ($order->is_confirm == 1) {
            $status_text = '已完成';
        }
        $goods_list = OrderDetail::find()->alias('od')
            ->leftJoin(['g' => Goods::tableName()], 'od.goods_id=g.id')
            ->where(['od.order_id' => $order->id, 'od.is_delete' => 0,])
            ->select('od.id AS order_detail_id,g.id AS goods_id,g.name,g.cover_pic,od.num,od.total_price,od.attr')
            ->asArray()->all();
        foreach ($goods_list as &$item) {
            $item['attr'] = json_decode($item['attr'], true);
        }
        unset($item);
        $express_data = (object)[];
        if ($order->express && $order->express_no) {
            $express_detail_form = new ExpressDetailForm();
            $express_detail_form->express = $order->express;
            $express_detail_form->express_no = $order->express_no;
            $express_detail_form->store_id = $order->store_id;
            $express_data = $express_detail_form->search();
        }
        $max_sub_price = $order->pay_price - $order->express_price - 0.01;
        if ($max_sub_price < 0)
            $max_sub_price = 0;
        $is_express = 1;
        if ($order->is_send == 1 && !$order->express && !$order->express_no) {
            $is_express = 0;
        }

        $list = Express::getExpressList();
        $expressList = [];
        foreach($list as $item){
            $expressList[]['express'] = $item['name'];
        }
        $default_express = '';
        if ($order->express) {
            $default_express = $order->express;
        } elseif (count($expressList)) {
            $default_express = $expressList[0]['express'];
        }
        return [
            'code' => 0,
            'data' => [
                'order' => [
                    'id' => $order->id,
                    'status_text' => $status_text,
                    'order_no' => $order->order_no,
                    'is_pay' => $order->is_pay,
                    'is_send' => $order->is_send,
                    'is_confirm' => $order->is_confirm,
                    'is_comment' => $order->is_comment,
                    'addtime' => date('Y-m-d H:i:s', $order->addtime),
                    'goods_list' => $goods_list,
                    'express_price' => $order->express_price,
                    'coupon_sub_price' => $order->coupon_sub_price,
                    'pay_price' => $order->pay_price,
                    'pay_type' => $order->pay_type,
                    'express_type' => $is_express == 1 ? '快递发货' : '无需物流',
                    'name' => $order->name,
                    'mobile' => $order->mobile,
                    'address' => $order->address,
                    'express' => $order->express,
                    'express_no' => $order->express_no,
                    'is_express' => $is_express,
                    'express_data' => $express_data,
                    'max_sub_price' => $max_sub_price,
                    'default_express' => $default_express,
                    'express_list' => $expressList,
                    'words' => $order->words,
                ],
            ],
        ];
    }
}
