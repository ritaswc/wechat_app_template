<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 *
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/5/2
 * Time: 14:03
 */


namespace app\modules\api\models\mch;

use app\models\Order;
use app\modules\api\models\ApiModel;

class TongjiMonthDataForm extends ApiModel
{
    public $mch_id;
    public $year;
    public $month;

    public function rules()
    {
        return [
            [['year', 'month'], 'required'],
            [['year', 'month'], 'integer'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $month_data = $this->getData($this->year, $this->month);
        $pre_month = $this->getPreMonth($this->year, $this->month);
        $pre_month_data = $this->getData($pre_month['year'], $pre_month['month']);
        if ($pre_month_data['month_count'] && $pre_month_data['month_count'] > 0) {
            $up_rate = ($pre_month_data['month_count'] - $pre_month_data['month_count']) * 100 / $pre_month_data['month_count'];
            if ($up_rate >= 100000000) {
                $up_rate = '-';
            } else {
                $up_rate = sprintf('%.2f', $up_rate);
            }
        } else {
            $up_rate = '-';
        }
        return [
            'code' => 0,
            'data' => [
                'daily_avg' => $month_data['daily_avg'],
                'month_count' => $month_data['month_count'],
                'up_rate' => $up_rate,
            ],
        ];
    }

    public function getData($year, $month)
    {
        $start_time = strtotime("{$year}-{$month}-01");
        $end_time = strtotime("{$year}-{$month}-01 +1 month -1 second");
        $month_days = date("t", strtotime("{$year}-{$month}"));

        $month_sum = Order::find()->alias('o')
            ->select("SUM() AS 'pay_price'")
            ->where([
                'AND',
                ['o.mch_id' => $this->mch_id,],
                ['o.is_pay' => 1,],
                ['>=', 'o.addtime', $start_time],
                ['<=', 'o.addtime', $end_time],
            ])
            ->sum('pay_price');
        $daily_avg = ($month_sum ? $month_sum : 0) / $month_days;

        return [
            'daily_avg' => sprintf('%.2f', $daily_avg),
            'month_count' => sprintf('%.2f', $month_sum),
        ];
    }

    private function getPreMonth($year, $month)
    {
        $time = strtotime("{$year}-{$month}-01 -1 month");
        return [
            'year' => intval(date('Y', $time)),
            'month' => intval(date('m', $time)),
        ];
    }
}
