<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/3/6
 * Time: 10:55
 */

namespace app\modules\api\models\mch;


use app\models\Mch;
use app\models\MchVisitLog;
use app\models\Order;
use app\models\OrderDetail;
use app\modules\api\models\ApiModel;

class MyshopForm extends ApiModel
{
    public $mch;

    public function search()
    {
        return [
            'code' => 0,
            'data' => [
                'data1' => $this->getPaySum(strtotime(date('Y-m-d 00:00:00')), strtotime(date('Y-m-d 23:59:59'))),
                'data2' => $this->getVisitCount(),
                'data3' => $this->getPayOrderCount(),
                'data4' => $this->getPayGoodsNum(),
                'data5' => '',
                'pc_url' => $this->getAdminUrl('mch'),
                'mch' => $this->mch()
            ],
        ];
    }

    public function mch() {
        $mch = Mch::find()->where(['store_id' => $this->getCurrentStoreId(), 'user_id' => $this->getCurrentUserId(), 'is_delete' => 0])->one();

        return $mch;
    }

    //获取付款金额
    public function getPaySum($start_time = null, $end_time = null)
    {
        $query = Order::find()->where([
            'mch_id' => $this->mch->id,
            'is_pay' => 1,
            'pay_type' => [1, 3],
        ]);
        if (is_int($start_time)) {
            $query->andWhere(['>=', 'addtime', $start_time]);
        }
        if (is_int($end_time)) {
            $query->andWhere(['<=', 'addtime', $end_time]);
        }

        $count = $query->sum('pay_price');
        return number_format($count ? $count : 0, 2, '.', '');
    }

    //浏览人数
    public function getVisitCount($start_time = null, $end_time = null, $format = true)
    {
        $query = MchVisitLog::find()->where([
            'mch_id' => $this->mch->id,
        ]);
        if (is_int($start_time)) {
            $query->andWhere(['>=', 'addtime', $start_time]);
        }
        if (is_int($end_time)) {
            $query->andWhere(['<=', 'addtime', $end_time]);
        }
        $count = $query->groupBy('user_id')->count('1');
        $count = $count ? $count : 0;
        if ($count >= 10000) {
            $count = sprintf('%.2f', $count) . '万';
        }
        return $count;
    }

    //付款订单数
    public function getPayOrderCount($start_time = null, $end_time = null, $format = true)
    {
        $query = Order::find()->where([
            'mch_id' => $this->mch->id,
            'is_pay' => 1,
            'pay_type' => [1, 3],
        ]);
        if (is_int($start_time)) {
            $query->andWhere(['>=', 'addtime', $start_time]);
        }
        if (is_int($end_time)) {
            $query->andWhere(['<=', 'addtime', $end_time]);
        }
        $count = $query->count('1');
        $count = $count ? $count : 0;
        if ($count >= 10000) {
            $count = sprintf('%.2f', $count) . '万';
        }
        return $count;
    }

    //付款件数
    public function getPayGoodsNum($start_time = null, $end_time = null, $format = true)
    {
        $query = OrderDetail::find()->alias('od')
            ->leftJoin(['o' => Order::tableName()], 'od.order_id=o.id')
            ->where([
                'o.mch_id' => $this->mch->id,
                'o.is_pay' => 1,
                'o.pay_type' => [1, 3],
                'od.is_delete' => 0,
            ]);

        if (is_int($start_time)) {
            $query->andWhere(['>=', 'o.addtime', $start_time]);
        }
        if (is_int($end_time)) {
            $query->andWhere(['<=', 'o.addtime', $end_time]);
        }
        $count = $query->sum('od.num');
        $count = $count ? $count : 0;
        if ($count >= 10000) {
            $count = sprintf('%.2f', $count) . '万';
        }
        return $count;
    }
}
