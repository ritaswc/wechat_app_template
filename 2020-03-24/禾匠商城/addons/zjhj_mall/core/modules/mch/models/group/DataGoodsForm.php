<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/6
 * Time: 12:00
 */

namespace app\modules\mch\models\group;

use app\models\PtGoods;
use app\models\PtOrder;
use app\models\PtOrderDetail;
use app\models\User;
use app\modules\mch\models\MchModel;
use yii\data\Pagination;

class DataGoodsForm extends MchModel
{
    public $store_id;
    public $status;

    public $limit;
    public $page;
    public $keyword;

    public function rules()
    {
        return [
            [['status', 'limit', 'page'], 'integer'],
            [['page'], 'default', 'value' => 1],
            [['limit'], 'default', 'value' => 20],
            [['status'], 'default', 'value' => 1],
            [['keyword'], 'trim'],
            [['keyword'], 'string'],
        ];
    }

    /**
     * @return array
     * $status //1--销量排序  2--销售额排序
     */
    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = PtGoods::find()->alias('g')->where(['g.is_delete' => 0, 'g.store_id' => $this->store_id])
            ->leftJoin(['od' => PtOrderDetail::tableName()], 'od.goods_id = g.id')
            ->leftJoin(['o' => PtOrder::tableName()], 'o.id = od.order_id')
            ->andWhere([
                'or',
                ['od.is_delete' => 0, 'o.is_delete' => 0, 'o.is_pay' => 1,'o.is_success'=>1],
                'isnull(od.id)'
            ])->groupBy('g.id');

        if ($this->keyword) {
            $query->andWhere(['like','g.name',$this->keyword]);
        }
        $count = $query->count();

        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit]);
        if ($this->status == 1) {
            $query->orderBy(['sales_volume'=>SORT_DESC]);
        } elseif ($this->status == 2) {
            $query->orderBy(['sales_price'=>SORT_DESC]);
        }
        $list = $query->select([
            'g.*', 'sum(case when isnull(o.id) then 0 else od.num end) as sales_volume',
            'sum(case when isnull(o.id) then 0 else od.total_price end) as sales_price'
        ])->offset($p->offset)->limit($p->limit)->asArray()->all();

        return [
            'list' => $list,
            'row_count' => $count,
            'pagination' => $p
        ];
    }

    /**
     * $status //1--消费金额排序  2--订单数排序
     */
    public function user_search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = User::find()->alias('u')->where(['u.store_id'=>$this->store_id,'u.is_delete'=>0])
            ->leftJoin(['o'=>PtOrder::tableName()], 'o.user_id = u.id')
            ->andWhere([
                'or',
                ['o.is_delete'=>0, 'o.is_pay'=>1,'o.is_success'=>1],
                'isnull(o.id)'
            ])->groupBy('u.id');
        if ($this->keyword) {
            $query->andWhere(['like','u.nickname',$this->keyword]);
        }
        $count = $query->count();
        $p = new Pagination(['totalCount'=>$count,'pageSize'=>$this->limit]);

        if ($this->status == 1) {
            $query->orderBy(['sales_price'=>SORT_DESC]);
        } elseif ($this->status == 2) {
            $query->orderBy(['sales_count'=>SORT_DESC]);
        }

        $list = $query->select([
            'u.*','sum(case when isnull(o.id) then 0 else o.pay_price end) as sales_price',
            'sum(case when isnull(o.id) then 0 else 1 end) as sales_count'
        ])->limit($p->limit)->offset($p->offset)->asArray()->all();
        return [
            'list'=>$list,
            'row_count'=>$count,
            'pagination'=>$p
        ];
    }
}
