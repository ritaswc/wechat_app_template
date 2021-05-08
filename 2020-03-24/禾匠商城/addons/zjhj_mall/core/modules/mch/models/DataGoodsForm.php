<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/4
 * Time: 16:21
 */

namespace app\modules\mch\models;

use app\models\Goods;
use app\models\Order;
use app\models\OrderDetail;
use yii\data\Pagination;

class DataGoodsForm extends MchModel
{
    public $store_id;
    public $status;//1--销量排序  2--销售额排序

    public $limit;
    public $page;
    public $keyword;

    public function rules()
    {
        return [
            [['status','limit','page'], 'integer'],
            [['page'],'default','value'=>1],
            [['limit'],'default','value'=>20],
            [['status'],'default','value'=>1],
            [['keyword'],'trim'],
            [['keyword'],'string'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = Goods::find()->alias('g')->where(['g.is_delete'=>0,'g.store_id'=>$this->store_id])
            ->leftJoin(['od'=>OrderDetail::tableName()], 'od.goods_id=g.id')
            ->leftJoin(['o'=>Order::tableName()], 'o.id=od.order_id')
            ->andWhere([
                'or',
                ['od.is_delete'=>0,'o.is_delete'=>0,'o.is_pay'=>1,'is_cancel'=>0],
                'isnull(o.id)'
            ]);
        $query->groupBy('g.id');
        if ($this->keyword) {
            $query->andWhere(['like','g.name',$this->keyword]);
        }
        $count = $query->count();
        if ($this->status == 1) {
            $query->orderBy(['sales_volume'=>SORT_DESC]);
        } elseif ($this->status) {
            $query->orderBy(['sales_price'=>SORT_DESC]);
        }
        $p = new Pagination(['totalCount'=>$count,'pageSize'=>$this->limit]);

        $list = $query->select([
            'g.*','sum(case when isnull(o.id) then 0 else od.num end) as sales_volume',
            'sum(case when isnull(o.id) then 0 else od.total_price end) as sales_price',
        ])->limit($p->limit)->offset($p->offset)->asArray()->all();

        return [
            'list'=>$list,
            'row_count'=>$count,
            'pagination'=>$p
        ];
    }
}
