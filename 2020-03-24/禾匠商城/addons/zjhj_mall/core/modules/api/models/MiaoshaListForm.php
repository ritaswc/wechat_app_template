<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/16
 * Time: 15:48
 */

namespace app\modules\api\models;

use app\models\Miaosha;
use app\models\MiaoshaGoods;
use app\models\MsGoods;

class MiaoshaListForm extends ApiModel
{
    public $store_id;
    public $date;
    public $time;

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $miaosha = Miaosha::findOne([
            'store_id' => $this->store_id,
        ]);
        if (!$miaosha) {
            return [
                'code' => 1,
                'msg' => '暂无秒杀安排',
            ];
        }
        $miaosha->open_time = json_decode($miaosha->open_time, true);
        $miaosha_list = MiaoshaGoods::find()->alias('mg')->where([
            'mg.open_date' => $this->date,
            'mg.start_time' => $miaosha->open_time,
            'mg.store_id' => $this->store_id,
            'mg.is_delete' => 0,
            'g.is_delete'  => 0,
            'g.status' => 1,
        ])->leftJoin(['g' => MsGoods::tableName()], 'mg.goods_id=g.id')
            ->groupBy('mg.start_time')->asArray()
            ->select('mg.*')->all();
        $has_active = false;
        foreach ($miaosha_list as $i => $item) {
            if ($item['start_time'] < $this->time) {
                $miaosha_list[$i]['status'] = 0;
                $miaosha_list[$i]['status_text'] = '已结束';
                $miaosha_list[$i]['active'] = false;
            } elseif ($item['start_time'] == $this->time) {
                $miaosha_list[$i]['status'] = 1;
                $miaosha_list[$i]['status_text'] = '进行中';
                $miaosha_list[$i]['active'] = true;
                $has_active = true;
            } else {
                $miaosha_list[$i]['status'] = 2;
                $miaosha_list[$i]['status_text'] = '即将开始';
                $miaosha_list[$i]['active'] = false;
            }
            $miaosha_list[$i]['title'] = $item['start_time'] < 10 ? ('0' . $item['start_time'] . ':00') : ($item['start_time'] . ':00');
            $miaosha_list[$i]['begin_time'] = strtotime($item['open_date'] . ' ' . $item['start_time'] . ':00:00');
            $miaosha_list[$i]['end_time'] = strtotime($item['open_date'] . ' ' . $item['start_time'] . ':59:59');
            $miaosha_list[$i]['now_time'] = time();
        }
        if (!$has_active) {
            foreach ($miaosha_list as $i => $item) {
                if ($item['status']==2) {
                    $miaosha_list[$i]['active'] = true;
                    break;
                }
            }
        }

        $next = MiaoshaGoods::find()->alias('mg')->where([
            'mg.start_time' => $miaosha->open_time,
            'mg.is_delete' => 0,
            'mg.store_id' => $this->store_id,
            'g.is_delete'  => 0,
            'g.status' => 1,
        ])->andWhere(['>','open_date',$this->date])
            ->leftJoin(['g' => MsGoods::tableName()], 'mg.goods_id=g.id')
            ->orderBy('open_date asc,start_time asc')
            ->select('mg.start_time,mg.open_date')->one();
            
        $under = [];
        if(!empty($next)){
            $next_list = MiaoshaGoods::find()->alias('mg')->where([
                'mg.start_time' => $next->start_time,
                'mg.open_date' => $next->open_date,
                'mg.store_id' => $this->store_id,
                'mg.is_delete' => 0,
                'g.is_delete'  => 0,
                'g.status' => 1,
            ])->andWhere(['>','open_date',$this->date])
                ->leftJoin(['g' => MsGoods::tableName()], 'mg.goods_id=g.id')
                ->orderBy(['g.sort' => SORT_ASC])
                ->select('mg.id,g.name,g.cover_pic,g.original_price AS price,mg.start_time,mg.attr,mg.goods_id')->asArray()->all();

            $now_time = intval(date('H'));
            foreach ($next_list as $i => $item) {
                $next_list[$i]['attr'] = json_decode($item['attr'], true);

                $total_sell_num = 0;
                $total_miaosha_num = 0;
                $miaosha_price = 0.00;

                foreach ($next_list[$i]['attr'] as $attr_item) {

                    $total_sell_num += empty($attr_item['sell_num']) ? 0 : intval($attr_item['sell_num']);

                    $total_miaosha_num += intval($attr_item['miaosha_num']);

                    if ($miaosha_price == 0) {
                        $miaosha_price = $attr_item['miaosha_price'];
                    } else {
                        $miaosha_price = min($miaosha_price, $attr_item['miaosha_price']);
                    }
                }
                
                $next_list[$i]['sell_num'] = $total_sell_num;
                $next_list[$i]['miaosha_num'] = $total_miaosha_num;
                $next_list[$i]['miaosha_price'] = $miaosha_price;
                $next_list[$i]['price'] = $item['price'];
                if ($item['start_time'] < $now_time) {
                    $next_list[$i]['status'] = 0;
                } elseif ($item['start_time'] == $now_time) {
                    $next_list[$i]['status'] = 1;
                } else {
                    $next_list[$i]['status'] = 2;
                }
            }
            $under['list'] = $next_list;
            $under['time'] = date('Y.m.d',strtotime($next->open_date)).' '.$next->start_time.':00';
        }

        return [
            'code' => 0,
            'data' => [
                'list' => $miaosha_list,
                'next_list'=>$under,
            ],
        ];
    }
}
