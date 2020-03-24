<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 *
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/5/2
 * Time: 11:46
 */


namespace app\modules\api\models\mch;

use app\modules\api\models\ApiModel;

class YearListForm extends ApiModel
{
    public function search()
    {
        $month_name_list = [
            1 => [
                'name_en' => 'JAN',
                'name_cn' => '1月',
            ],
            2 => [
                'name_en' => 'FEB',
                'name_cn' => '2月',
            ],
            3 => [
                'name_en' => 'MAR',
                'name_cn' => '3月',
            ],
            4 => [
                'name_en' => 'APR',
                'name_cn' => '4月',
            ],
            5 => [
                'name_en' => 'MAY',
                'name_cn' => '5月',
            ],
            6 => [
                'name_en' => 'JUNE',
                'name_cn' => '6月',
            ],
            7 => [
                'name_en' => 'JULY',
                'name_cn' => '7月',
            ],
            8 => [
                'name_en' => 'AUG',
                'name_cn' => '8月',
            ],
            9 => [
                'name_en' => 'SEPT',
                'name_cn' => '9月',
            ],
            10 => [
                'name_en' => 'OCT',
                'name_cn' => '10月',
            ],
            11 => [
                'name_en' => 'NOV',
                'name_cn' => '11月',
            ],
            12 => [
                'name_en' => 'DEC',
                'name_cn' => '12月',
            ],
        ];
        $start_year = 2017;
        $start_month = 1;
        $end_year = intval(date('Y'));
        $end_month = intval(date('m'));
        $list = [];
        for ($i = 0; ($start_year + $i) <= $end_year; $i++) {
            $month_list = [];
            if ($start_year + $i == $end_year) {
                for ($j = 1; $j <= $end_month; $j++) {
                    $month_list[] = [
                        'month' => $j,
                        'name_en' => $month_name_list[$j]['name_en'],
                        'name_cn' => $month_name_list[$j]['name_cn'],
                        'active' => false,
                    ];
                }
            } else {
                for ($j = 1; $j <= 12; $j++) {
                    $month_list[] = [
                        'month' => $j,
                        'name_en' => $month_name_list[$j]['name_en'],
                        'name_cn' => $month_name_list[$j]['name_cn'],
                        'active' => false,
                    ];
                }
            }
            $list[] = [
                'year' => $start_year + $i,
                'month_list' => $month_list,
                'active' => false,
            ];
        }
        $list[count($list) - 1]['active'] = true;
        $list[count($list) - 1]['month_list'][count($list[count($list) - 1]['month_list']) - 1]['active'] = true;
        return [
            'code' => 0,
            'data' => [
                'year_list' => $list,
                'current_year' => $end_year,
                'current_month' => $end_month,
            ],
        ];
    }
}
