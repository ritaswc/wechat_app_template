<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/18
 * Time: 19:13
 */

namespace app\modules\api\models;

use app\models\Goods;
use app\models\Mch;
use app\models\Option;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderRefund;
use yii\data\Pagination;
use yii\helpers\VarDumper;

class OrderListForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $status;
    public $page;
    public $limit;
    public $order_id;

    public function rules()
    {
        return [
            [['page', 'limit', 'status',], 'integer'],
            [['page',], 'default', 'value' => 1],
            [['limit',], 'default', 'value' => 20],
            [['order_id'],'trim']
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = Order::find()->where([
            'is_delete' => 0,
            'store_id' => $this->store_id,
            'user_id' => $this->user_id,
            'is_cancel' => 0
        ]);
        if ($this->status == 0) {//待付款
            $query->andWhere([
                'is_pay' => 0,
            ]);
        }
        if ($this->status == 1) {//待发货
            $query->andWhere([
                'is_send' => 0,
            ])->andWhere(['or', ['is_pay' => 1], ['pay_type' => 2]]);
        }
        if ($this->status == 2) {//待收货
            $query->andWhere([
                'is_send' => 1,
                'is_confirm' => 0,
            ]);
        }
        if ($this->status == 3) {//已完成
            $query->andWhere([
                'is_confirm' => 1,
            ]);
        }
        if ($this->status == 4) {//售后订单
            return $this->getRefundList();
        }
        $query->andWhere(['is_recycle' => 0]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1, 'pageSize' => $this->limit]);
        /* @var Order[] $list */
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('addtime DESC')->all();
        $new_list = [];
        foreach ($list as $order) {
            $order_detail_list = OrderDetail::findAll(['order_id' => $order->id, 'is_delete' => 0]);
            $goods_list = [];
            foreach ($order_detail_list as $order_detail) {
                $goods = Goods::findOne($order_detail->goods_id);
                if (!$goods) {
                    continue;
                }
                $goods_pic = isset($order_detail->pic) ? $order_detail->pic ?: $goods->getGoodsPic(0)->pic_url : $goods->getGoodsPic(0)->pic_url;
                $goods_list[] = (object)[
                    'goods_id' => $goods->id,
                    'goods_pic' => $goods_pic,
//                    'goods_pic' => $goods->getGoodsPic(0)->pic_url,
                    'goods_name' => $goods->name,
                    'num' => $order_detail->num,
                    'price' => $order_detail->total_price,
                    'attr_list' => json_decode($order_detail->attr),
                ];
            }
            $qrcode = null;
            if ($order->mch_id) {
                $mch = Mch::findOne($order->mch_id);
                $mch = [
                    'id' => $mch->id,
                    'name' => $mch->name,
                    'logo' => $mch->logo,
                ];
            } else {
                $mch = [
                    'id' => 0,
                    'name' => '平台自营',
                    'logo' => '',
                ];
            }
            $orderRefund = OrderRefund::find()->where(['store_id' => $order->store_id, 'order_id' => $order->id])->exists();
            $new_list[] = (object)[
                'order_id' => $order->id,
                'order_no' => $order->order_no,
                'addtime' => date('Y-m-d H:i', $order->addtime),
                'goods_list' => $goods_list,
                'total_price' => $order->total_price,
                'pay_price' => $order->pay_price,
                'is_pay' => $order->is_pay,
                'is_send' => $order->is_send,
                'is_confirm' => $order->is_confirm,
                'is_comment' => $order->is_comment,
                'apply_delete' => $order->apply_delete,
                'is_offline' => $order->is_offline,
                'qrcode' => $qrcode,
                'offline_qrcode' => $order->offline_qrcode,
                'express' => $order->express,
                'mch' => $mch,
                'pay_type' => $order->pay_type,
                'refund' => $orderRefund,
                'currency' => $order->currency
            ];
        }
        $pay_type_list = OrderData::getPayType($this->store_id, array(), ['huodao']);

        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'row_count' => $count,
                'page_count' => $pagination->pageCount,
                'list' => $new_list,
                'pay_type_list' => $pay_type_list
            ],
        ];
    }

    private function getRefundList()
    {
        $query = OrderRefund::find()->alias('or')
            ->leftJoin(['od' => OrderDetail::tableName()], 'od.id=or.order_detail_id')
            ->leftJoin(['o' => Order::tableName()], 'o.id=or.order_id')
            ->where([
                'or.store_id' => $this->store_id,
                'or.user_id' => $this->user_id,
                'or.is_delete' => 0,
                'o.is_delete' => 0,
                'od.is_delete' => 0,
            ]);
        if($this->order_id){
            $query->andWhere(['o.id' => $this->order_id]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1, 'pageSize' => $this->limit]);
        $list = $query->select('o.id AS order_id,o.order_no,or.id AS order_refund_id,od.goods_id,or.addtime,od.num,od.total_price,od.attr,or.refund_price,or.type,or.status,or.is_agree,or.is_user_send')->limit($pagination->limit)->offset($pagination->offset)->orderBy('or.addtime DESC')->asArray()->all();
        $new_list = [];
        foreach ($list as $item) {
            $goods = Goods::findOne($item['goods_id']);
            if (!$goods) {
                continue;
            }
            $new_list[] = (object)[
                'order_id' => intval($item['order_id']),
                'order_no' => $item['order_no'],
                'goods_list' => [(object)[
                    'goods_id' => intval($goods->id),
                    'goods_pic' => $goods->getGoodsPic(0)->pic_url,
                    'goods_name' => $goods->name,
                    'num' => intval($item['num']),
                    'price' => doubleval(sprintf('%.2f', $item['total_price'])),
                    'attr_list' => json_decode($item['attr']),
                ]],
                'addtime' => date('Y-m-d H:i', $item['addtime']),
                'refund_price' => doubleval(sprintf('%.2f', $item['refund_price'])),
                'refund_type' => $item['type'],
                'refund_status' => $item['status'],
                'order_refund_id' => $item['order_refund_id'],
                'is_agree' => $item['is_agree'],
                'is_user_send' => $item['is_user_send'],
            ];
        }
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => [
                'row_count' => $count,
                'page_count' => $pagination->pageCount,
                'list' => $new_list,
            ],
        ];
    }

    public static function getCountData($store_id, $user_id)
    {
        $form = new OrderListForm();
        $form->limit = 1;
        $form->store_id = $store_id;
        $form->user_id = $user_id;
        $data = [];

        $form->status = -1;
        $res = $form->search();

        $data['all'] = $res['data']['row_count'];

        $form->status = 0;
        $res = $form->search();
        $data['status_0'] = $res['data']['row_count'];

        $form->status = 1;
        $res = $form->search();
        $data['status_1'] = $res['data']['row_count'];

        $form->status = 2;
        $res = $form->search();
        $data['status_2'] = $res['data']['row_count'];

        $form->status = 3;
        $res = $form->search();
        $data['status_3'] = $res['data']['row_count'];

        return $data;
    }
}
