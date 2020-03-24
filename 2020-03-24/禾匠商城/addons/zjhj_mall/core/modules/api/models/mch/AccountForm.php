<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 *
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/4/28
 * Time: 12:03
 */


namespace app\modules\api\models\mch;

use app\models\Goods;
use app\models\Mch;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderRefund;
use app\modules\api\models\ApiModel;

class AccountForm extends ApiModel
{
    public $mch_id;

    public function search()
    {
        $mch = Mch::findOne($this->mch_id);
        if (!$mch) {
            return [
                'code' => 1,
                'msg' => '商户不存在。',
            ];
        }


        $query = Order::find()->alias('o')
            ->leftJoin(['od' => OrderDetail::tableName()], 'od.order_id=o.id')
            ->leftJoin(['or' => OrderRefund::tableName()], 'or.order_detail_id=od.id')
            ->leftJoin(['g' => Goods::tableName()], 'g.id=od.goods_id')
            ->where([
                'o.mch_id' => $this->mch_id,
                'o.is_pay' => Order::IS_PAY_TRUE,
            ]);
        $mchOrders = $query->orderBy('addtime DESC')
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

        return [
            'code' => 0,
            'data' => [
                'header_bg' => \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/shop/img/mch-account-header-bg.png',
                'account_money' => number_format($mch->account_money, 2, '.', ''),
                'rest_money' => '0',
                'desc' => '商户的手续费为' . $mch->transfer_rate . '/1000，即每笔成交订单可收入的金额=订单支付金额×(1-' . $mch->transfer_rate . '÷1000)。',
                'no_settle_price' => $no_settle_price,
                'settle_price' => $settle_price,
            ],
        ];
    }
}
