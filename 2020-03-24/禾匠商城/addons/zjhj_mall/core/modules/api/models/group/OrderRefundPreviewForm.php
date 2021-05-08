<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/7
 * Time: 10:55
 */

namespace app\modules\api\models\group;

use app\models\Goods;
use app\models\Order;
use app\models\OrderDetail;
use app\models\PtGoods;
use app\models\PtOrder;
use app\models\PtOrderDetail;
use app\modules\api\models\ApiModel;

class OrderRefundPreviewForm extends ApiModel
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

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $data = PtOrderDetail::find()->alias('od')
            ->leftJoin(['g' => PtGoods::tableName()], 'od.goods_id=g.id')
            ->leftJoin(['o' => PtOrder::tableName()], 'od.order_id=o.id')
            ->where([
                'o.is_delete' => 0,
                'o.user_id' => $this->user_id,
                'o.store_id' => $this->store_id,
                'od.order_id' => $this->order_id,
            ])
            ->select('od.id AS order_detail_id,g.id AS goods_id,g.name,od.attr,od.num,od.total_price,o.pay_price,g.cover_pic AS goods_pic')
            ->asArray()
            ->one();
        if (!$data) {
            return [
                'code' => 1,
                'msg' => '订单不存在',
            ];
        }
        $data['attr'] = json_decode($data['attr']);
//        $data['goods_pic'] = Goods::getGoodsPicStatic($data['goods_id'])->pic_url;
        $data['max_refund_price'] = min($data['total_price'], $data['pay_price']);
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => $data,
        ];
    }
}
