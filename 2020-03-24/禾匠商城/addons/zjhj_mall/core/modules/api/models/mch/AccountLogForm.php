<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 *
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/4/29
 * Time: 14:55
 */


namespace app\modules\api\models\mch;

use app\models\Goods;
use app\models\MchAccountLog;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderRefund;
use app\modules\api\models\ApiModel;
use yii\data\Pagination;

class AccountLogForm extends ApiModel
{
    public $mch_id;
    public $type;
    public $year;
    public $month;
    public $page;
    public $settle_type;

    public function rules()
    {
        return [
            [['type', 'year', 'month', 'page', 'settle_type'], 'integer'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = MchAccountLog::find()->where([
            'mch_id' => $this->mch_id,
        ]);
        if ($this->type) {
            $query->andWhere(['type' => $this->type]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('addtime DESC')
            ->asArray()->all();
        foreach ($list as &$item) {
            $item['addtime'] = date('Y-m-d H:i:s', $item['addtime']);
        }
        return [
            'code' => 0,
            'data' => [
                'list' => $list,
            ],
        ];
    }


    public function settleLog()
    {
        $query = Order::find()->alias('o')
            ->leftJoin(['od' => OrderDetail::tableName()], 'od.order_id=o.id')
            ->leftJoin(['or' => OrderRefund::tableName()], 'or.order_detail_id=od.id')
            ->leftJoin(['g' => Goods::tableName()], 'g.id=od.goods_id')
            ->where([
                'o.mch_id' => $this->mch_id,
                'o.is_pay' => Order::IS_PAY_TRUE,
            ]);

        //已结算
        if ($this->settle_type) {
            $query->andWhere(['o.is_sale' => Order::IS_SALE_TRUE]);
        } else {
            // 未结算
            $query->andWhere(['o.is_sale' => Order::IS_SALE_FALSE]);
        }

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1]);
        $mchOrders = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('addtime DESC')
            ->select('o.id oid, o.pay_price, o.is_sale, o.order_no, o.is_confirm, o.is_send, o.addtime, or.status  refund_status, or.refund_price, od.id od_id, g.name')
            ->orderBy('o.addtime DESC')
            ->asArray()->all();
        $ids = [];
        $list = [];

        foreach ($mchOrders as $item) {
            $newItem1 = [];

            if (!in_array($item['oid'], $ids)) {
                $ids[] = $item['oid'];

                if ((int)$item['refund_status'] !== 1) {
                    $newItem1['price'] = ((int)$item['refund_status'] === 1) ? number_format($item['pay_price'] - $item['refund_price'], 2) : $item['pay_price'];
                    $newItem1['order_no'] = $item['order_no'];
//                    if ($item['order_no'] == '20181003151744319670') {
//                        dd($item);
//                    }
                    $item['name'] ? $newItem1['goods_name'] .= $item['name'] : '';
                    (int)$item['is_send'] === 0 ? $newItem1['order_status'] = '待发货' : $newItem1['order_status'] = '待收货';
                    (int)$item['is_confirm'] === 1 ? $newItem1['order_status'] = '已收货' : '';
                }
            } else {
                if ((int)$item['refund_status'] === 1) {
                    $list[$item['oid']]['price'] = number_format($list[$item['oid']]['price'] - $item['refund_price'], 2);
                }
//                $list[$item['oid']]['name'] .= ',' . $item['name'];
            }

            !empty($newItem1) ? $list[$item['oid']] = $newItem1 : '';
        }

        $list = array_values($list);


        return [
            'code' => 0,
            'msg' => '获取成功',
            'data' => [
                'list' => $list,
                'pagination' => $pagination
            ]
        ];
    }

    /**
     * 未结算
     * @return array
     */
    public function noSettleLog()
    {
        $query = Order::find()->alias('o')
            ->leftJoin(['od' => OrderDetail::tableName()], 'od.order_id=o.id')
            ->leftJoin(['or' => OrderRefund::tableName()], 'or.order_detail_id=od.id')
            ->leftJoin(['g' => Goods::tableName()], 'g.id=od.goods_id')
            ->where([
                'o.mch_id' => $this->mch_id,
                'o.is_pay' => Order::IS_PAY_TRUE,
            ]);

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1]);
        $mchOrders = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('addtime DESC')
            ->select('o.id oid, o.pay_price, o.is_sale, o.order_no, o.is_confirm, o.is_send, o.addtime, or.status refund_status, or.refund_price, od.id od_id, g.name')
            ->asArray()->all();

        $ids = [];
        $payPriceCount = 0;
        $refundPriceCount = 0;

        $settleIds = [];
        $settlePayPriceCount = 0;
        $settleRefundPriceCount = 0;

        foreach ($mchOrders as $item) {
            //未结算数据
            if ((int)$item['is_sale'] === 0 && !in_array($item['oid'], $ids)) {
                $ids[] = $item['oid'];
                $payPriceCount += $item['pay_price'];
            }
            if ((int)$item['is_sale'] === 0 && $item['refund_status'] && (int)$item['refund_status'] === 1) {
                $refundPriceCount += $item['refund_price'];
            }

            // 已结算数据
            if ((int)$item['is_sale'] === 1 && !in_array($item['oid'], $settleIds)) {
                $settleIds[] = $item['oid'];
                $settlePayPriceCount += $item['pay_price'];
            }
            if ((int)$item['is_sale'] === 1 && $item['refund_status'] && (int)$item['refund_status'] === 1) {
                $settleRefundPriceCount += $item['refund_price'];
            }
        }


        //未过售后 未结算金额
        $no_settle_price = number_format(floatval($payPriceCount - $refundPriceCount), 2);
        //过售后 已结算金额
        $settle_price = number_format(floatval($settlePayPriceCount - $settleRefundPriceCount), 2);

        $no_settle_price = $no_settle_price;
        $settle_price = $settle_price;
    }
}
