<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/5
 * Time: 10:55
 */

namespace app\modules\mch\models;

use app\models\Level;
use app\models\Order;
use app\models\User;
use yii\data\Pagination;

class DataUserForm extends MchModel
{
    public $store_id;
    public $status; //1--按消费金额 2--按订单数

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
        $query = User::find()->alias('u')->where(['u.store_id'=>$this->store_id,'u.is_delete'=>0])
            ->leftJoin(['o'=>Order::tableName()], 'o.user_id = u.id')
            ->andWhere([
                'or',
                ['o.is_delete'=>0,'o.is_pay'=>1],
                'isnull(o.id)'
            ]);
        $query->groupBy('u.id');
        if ($this->keyword) {
            $query->andWhere(['like','u.nickname',$this->keyword]);
        }

        $count = $query->count();
        if ($this->status == 1) {
            $query->orderBy(['sales_price'=>SORT_DESC]);
        } elseif ($this->status == 2) {
            $query->orderBy(['sales_count'=>SORT_DESC]);
        }
        $p = new Pagination(['totalCount'=>$count,'pageSize'=>$this->limit]);

        $list = $query->select([
            'u.*','sum(case when isnull(o.id) then 0 else o.pay_price end) as sales_price',
            'sum(case when isnull(o.id) then 0 else 1 end) as sales_count'
        ])->limit($p->limit)->offset($p->offset)->asArray()->all();
        foreach ($list as $index => $value) {
            $level = Level::find()->where(['level'=>$value['level'],'store_id'=>$this->store_id,'is_delete'=>0])->one();
            $list[$index]['level_name'] = "普通用户";
            if ($level) {
                $list[$index]['level_name'] = $level['name'];
            }
        }
        return [
            'list'=>$list,
            'row_count'=>$count,
            'pagination'=>$p
        ];
    }
}
