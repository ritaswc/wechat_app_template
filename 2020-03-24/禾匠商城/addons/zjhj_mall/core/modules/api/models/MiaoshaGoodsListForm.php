<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/17
 * Time: 10:01
 */

namespace app\modules\api\models;

use app\models\Goods;
use app\models\MiaoshaGoods;
use app\models\MsGoods;
use yii\data\Pagination;

class MiaoshaGoodsListForm extends ApiModel
{
    public $store_id;
    public $time;
    public $date;

    public $page;

    public function rules()
    {
        return [
            [['time',], 'required'],
            [['time',], 'integer', 'min' => 0, 'max' => 23],
            [['page'], 'default', 'value' => 1],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = MiaoshaGoods::find()->alias('mg')->leftJoin(['g' => MsGoods::tableName()], 'g.id=mg.goods_id')
            ->where([
                'mg.store_id' => $this->store_id,
                'mg.is_delete' => 0,
                'g.store_id' => $this->store_id,
                'g.is_delete' => 0,
                'mg.open_date' => $this->date,
                'mg.start_time' => $this->time,
                'g.status' => 1,
            ]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1, 'pageSize' => 20]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy(['g.sort' => SORT_ASC])
            ->select('mg.id,g.name,g.cover_pic,g.original_price AS price,mg.start_time,mg.attr,mg.goods_id')->asArray()->all();
        $now_time = intval(date('H'));
        foreach ($list as $i => $item) {
//            if (!$list[$i]['cover_pic'])
//                $list[$i]['cover_pic'] = MsGoods::getGoodsPicStatic($item['id'])->pic_url;
            $list[$i]['attr'] = json_decode($item['attr'], true);
            $total_sell_num = 0;
            $total_miaosha_num = 0;
            $miaosha_price = 0.00;
            foreach ($list[$i]['attr'] as $attr_item) {
                $total_sell_num += empty($attr_item['sell_num']) ? 0 : intval($attr_item['sell_num']);
                $total_miaosha_num += intval($attr_item['miaosha_num']);
                if ($miaosha_price == 0) {
                    $miaosha_price = $attr_item['miaosha_price'];
                } else {
                    $miaosha_price = min($miaosha_price, $attr_item['miaosha_price']);
                }
            }
            $list[$i]['sell_num'] = $total_sell_num;
            $list[$i]['miaosha_num'] = $total_miaosha_num;
            $list[$i]['miaosha_price'] = $miaosha_price;
            $list[$i]['price'] = $item['price'];
            if ($item['start_time'] < $now_time) {
                $list[$i]['status'] = 0;
            } elseif ($item['start_time'] == $now_time) {
                $list[$i]['status'] = 1;
            } else {
                $list[$i]['status'] = 2;
            }
        }
        return [
            'code' => 0,
            'data' => [
                'list' => $list,
            ],
        ];
    }
}
