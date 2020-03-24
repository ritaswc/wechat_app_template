<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/11/17
 * Time: 10:30
 */

namespace app\modules\mch\models;

use app\models\MiaoshaGoods;
use app\models\MsGoods;

class MiaoshaDateForm extends MchModel
{
    public $store_id;
    public $date;

    public function rules()
    {
        return [
            ['store_id', 'required'],
            ['date', 'default', 'value' => function ($attr) {
                return $this->date ? $this->date : date('Y-m-d');
            }],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = MiaoshaGoods::find()->alias('mg')
            ->leftJoin(['g' => MsGoods::tableName()], 'g.id=mg.goods_id')
            ->where([
                'AND',
                [
                    'mg.store_id' => $this->store_id,
                    'mg.is_delete' => 0,
                    'mg.open_date' => $this->date,
                    'g.store_id' => $this->store_id,
                    'g.is_delete' => 0,
                ],
            ]);
        $list = $query->select('mg.id,mg.start_time,mg.open_date')
            ->orderBy('mg.start_time ASC')
            ->asArray()
            ->groupBy('start_time')
            ->all();
        foreach ($list as $i => $item) {
            $goods_list = MiaoshaGoods::find()->alias('mg')
                ->leftJoin(['g' => MsGoods::tableName()], 'mg.goods_id=g.id')
                ->where([
                    'AND',
                    [
                        'mg.store_id' => $this->store_id,
                        'mg.is_delete' => 0,
                        'mg.start_time' => $item['start_time'],
                        'mg.open_date' => $item['open_date'],
                        'g.store_id' => $this->store_id,
                        'g.is_delete' => 0,
                    ],
                ])
                ->select('g.id,g.name')
                ->asArray()
                ->all();
            $list[$i]['goods_list'] = $goods_list;
        }
        return [
            'code' => 0,
            'data' => [
                'date' => $this->date,
                'list' => $list,
            ],
        ];
    }
}
