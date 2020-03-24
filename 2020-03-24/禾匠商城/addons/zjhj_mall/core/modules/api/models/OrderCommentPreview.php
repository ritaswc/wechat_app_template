<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/15
 * Time: 17:23
 */

namespace app\modules\api\models;

use app\models\Goods;
use app\models\IntegralOrder;
use app\models\IntegralOrderDetail;
use app\models\Order;
use app\models\OrderDetail;

class OrderCommentPreview extends ApiModel
{
    public $store_id;
    public $user_id;
    public $order_id;
    public $type;
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [[ 'type'], 'string'],
        ];
    }
    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        switch ($this->type) {
            case 'mall':
                $order = Order::findOne([
                    'is_delete' => 0,
                    'store_id' => $this->store_id,
                    'user_id' => $this->user_id,
                    'id' => $this->order_id,
                ]);
                break;
            default:
                $order = IntegralOrder::findOne([
                    'is_delete' => 0,
                    'store_id' => $this->store_id,
                    'user_id' => $this->user_id,
                    'id' => $this->order_id,
                ]);
                break;
        }
        if (!$order) {
            return [
                'code' => 1,
                'msg' => '订单不存在！',
            ];
        }
        if ($order->is_confirm != 1) {
            return [
                'code' => 1,
                'msg' => '订单尚未确认收货，无法评价！',
            ];
        }
        if ($order->is_comment == 1) {
            return [
                'code' => 1,
                'msg' => '订单已评价！',
            ];
        }

        switch ($this->type) {
            case 'mall':
                $order_detail_list = OrderDetail::find()
                    ->where(['order_id' => $order->id, 'is_delete' => 0])
                    ->select('id order_detail_id,goods_id')->asArray()->all();

                foreach ($order_detail_list as $i => $order_detail) {
                    $order_detail_list[$i]['goods_pic'] = Goods::getGoodsPicStatic($order_detail['goods_id'])->pic_url;
                }
                return [
                    'code' => 0,
                    'msg' => 'success',
                    'data' => [
                        'order_id' => $order->id,
                        'goods_list' => $order_detail_list,
                    ],
                ];
                break;
            default:
                $order_detail_list = IntegralOrderDetail::find()
                    ->where(['order_id' => $order->id, 'is_delete' => 0])
                    ->select('id order_detail_id,goods_id,pic goods_pic')->asArray()->all();
                return [
                    'code' => 0,
                    'msg' => 'success',
                    'data' => [
                        'order_id' => $order->id,
                        'goods_list' => $order_detail_list,
                    ],
                ];
                break;
        }
    }
}
