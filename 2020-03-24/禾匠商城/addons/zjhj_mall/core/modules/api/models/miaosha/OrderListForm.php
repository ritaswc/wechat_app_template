<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/18
 * Time: 19:13
 */

namespace app\modules\api\models\miaosha;

use app\models\Goods;
use app\models\MsGoods;
use app\models\MsOrder;
use app\models\MsOrderRefund;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderRefund;
use app\modules\api\models\ApiModel;
use app\modules\api\models\OrderData;
use yii\data\Pagination;
use yii\helpers\VarDumper;

class OrderListForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $status;
    public $page;
    public $limit;

    public function rules()
    {
        return [
            [['page', 'limit', 'status',], 'integer'],
            [['page',], 'default', 'value' => 1],
            [['limit',], 'default', 'value' => 20],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = MsOrder::find()->where([
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
            ])->andWhere(['or',['is_pay'=>1],['pay_type'=>2]]);
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
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1, 'pageSize' => $this->limit]);
        /* @var Order[] $list */
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('addtime DESC')->all();
        $new_list = [];
        foreach ($list as $order) {
            $goods_list = [];
            $goods = MsGoods::findOne($order->goods_id);
            if (!$goods) {
                continue;
            }
            $goods_pic = isset($order->pic) ? $order->pic ?: $goods->cover_pic : $goods->cover_pic;
            $goods_list[] = (object)[
                'goods_id' => $goods->id,
                'goods_pic' => $goods_pic,
                'goods_name' => $goods->name,
                'num' => $order->num,
                'price' => $order->total_price,
                'attr_list' => json_decode($order->attr),
            ];

            $is_payment = json_decode($goods->payment, true);
            $pay_type_list = OrderData::getPayType($this->store_id, $is_payment, ['huodao']);
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
                'offline_qrcode' => $order->offline_qrcode,
                'express' => $order->express,
                'pay_type_list' => $pay_type_list,
                'pay_type'=>$order->pay_type
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

    private function getRefundList()
    {
        $query = MsOrderRefund::find()->alias('or')
            ->leftJoin(['o' => MsOrder::tableName()], 'o.id=or.order_id')
            ->where([
                'or.store_id' => $this->store_id,
                'or.user_id' => $this->user_id,
                'or.is_delete' => 0,
                'o.is_delete' => 0,
            ]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1, 'pageSize' => $this->limit]);
        $list = $query->select('o.id AS order_id,o.order_no,or.id AS order_refund_id,o.goods_id,or.addtime,o.num,o.total_price,o.attr,or.refund_price,or.type,or.status, or.is_agree, or.is_user_send')->limit($pagination->limit)->offset($pagination->offset)->orderBy('or.addtime DESC')->asArray()->all();
        $new_list = [];
        foreach ($list as $item) {
            $goods = MsGoods::findOne($item['goods_id']);
            if (!$goods) {
                continue;
            }
            $new_list[] = (object)[
                'order_id' => intval($item['order_id']),
                'order_no' => $item['order_no'],
                'goods_list' => [(object)[
                    'goods_id' => intval($goods->id),
                    'goods_pic' => $goods->cover_pic,
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
        $form->limit = 0;
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
