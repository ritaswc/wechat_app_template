<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/11/16
 * Time: 14:03
 */

namespace app\modules\mch\models;

use app\models\MiaoshaGoods;

class MiaoshaCalendar extends MchModel
{
    public $store_id;
    public $month;

    public function rules()
    {
        return [
            [['store_id',], 'required'],
            [['store_id', 'month'], 'integer'],
            [['month'], 'default', 'value' => 0],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        list($begin_date, $end_date) = self::getDate($this->month);
        $query = MiaoshaGoods::find()->alias('mg')
            ->select('mg.open_date,COUNT(mg.id) as miaosha_count')
            ->where([
                'AND',
                ['mg.is_delete' => 0, 'mg.store_id' => $this->store_id,],
                ['>=', 'mg.open_date', $begin_date],
                ['<=', 'mg.open_date', $end_date],
            ])->groupBy('mg.open_date')->orderBy('mg.open_date ASC');
        $list = $query->asArray()->all();
        $new_list = [];
        foreach ($list as $item) {
            $new_list[str_replace('-', '', $item['open_date'])] = $item;
        }
        return [
            'code' => 0,
            'data' => [
                'list' => $new_list,
            ],
        ];
    }

    public static function getDate($month)
    {
        $month = intval($month);
        $begin_date = date('Y-m-d', strtotime(date('Y-m-01') . " {$month} month"));
        $end_date = date('Y-m-d', strtotime(date('Y-m-01') . " " . ($month + 1) . " month -1 day"));
        return [$begin_date, $end_date];
    }

    public static function getWeek($date)
    {
        //强制转换日期格式
        $date_str = date('Y-m-d', strtotime($date));

        //封装成数组
        $arr = explode("-", $date_str);

        //参数赋值
        //年
        $year = $arr[0];

        //月，输出2位整型，不够2位右对齐
        $month = sprintf('%02d', $arr[1]);

        //日，输出2位整型，不够2位右对齐
        $day = sprintf('%02d', $arr[2]);

        //时分秒默认赋值为0；
        $hour = $minute = $second = 0;

        //转换成时间戳
        $strap = mktime($hour, $minute, $second, $month, $day, $year);

        //获取数字型星期几
        $number_wk = date("w", $strap);

        if ($number_wk == 0) {
            return 7;
        }
        return $number_wk;
    }
}
