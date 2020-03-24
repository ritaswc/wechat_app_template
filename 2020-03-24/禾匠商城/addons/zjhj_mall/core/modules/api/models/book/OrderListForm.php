<?php
/**
 * Created by PhpStorm.
 * User: peize
 * Date: 2017/12/19
 * Time: 14:30
 */

namespace app\modules\api\models\book;

use app\models\Option;
use app\models\YyGoods;
use app\models\YyOrder;
use app\modules\api\models\ApiModel;
use app\modules\api\models\OrderData;
use yii\data\Pagination;

class OrderListForm extends ApiModel
{
    public $store_id;
    public $user_id;
    public $status;
    public $page;
    public $limit;

    public function rules()
    {
        return [
            [['page', 'limit', 'status',], 'integer'],
            [['page',], 'default', 'value' => 1],
            [['limit',], 'default', 'value' => 5],
        ];
    }


    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $query = YyOrder::find()
            ->alias('o')
            ->select([
                'o.*',
                'g.name AS goods_name',
                'g.cover_pic',
                'g.original_price',
            ])
            ->where([
            'o.is_delete' => 0,
            'o.store_id' => $this->store_id,
            'o.user_id' => $this->user_id,
            'o.is_cancel' => 0,
            ]);
        $query->leftJoin(['g'=>YyGoods::tableName()], 'o.goods_id=g.id');
        if ($this->status == 0) {//待付款
            $query->andWhere([
                'o.is_pay' => 0,
                'o.is_cancel' => 0,
            ]);
        }
        if ($this->status == 1) {//待使用
            $query->andWhere([
                'o.is_pay' => 1,
                'o.is_use' => 0,
                'o.is_cancel' => 0,
            ])->andWhere(['or',
                [
                    'o.apply_delete' => 0,
                    'o.is_refund' => 0,
                ],
                [
                    'o.apply_delete' => 1,
                    'o.is_refund' => 2,
                ]
            ]);
        }
        if ($this->status == 2) {// 已使用
            $query->andWhere([
                'o.is_pay' => 1,
                'o.is_use' => 1,
//                'o.is_comment' => 0,
            ]);
        }
        if ($this->status == 3) {//退款
            $query->andWhere([
                'o.is_pay' => 1,
                'o.apply_delete' => 1,
            ]);
        }

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1, 'pageSize' => $this->limit]);
        $list = $query
            ->limit($pagination->limit)
            ->offset($pagination->offset)
            ->orderBy('addtime DESC')
            ->asArray()
            ->all();

        $isPayment = Option::get('yy_payment', $this->store_id, 'admin');
        if ($isPayment) {
            $isPayment = \Yii::$app->serializer->decode($isPayment);
        } else {
            $isPayment = ['wechat' => 1];
        }
        $pay_type_list = OrderData::getPayType($this->store_id, $isPayment, ['huodao']);

        return [
            'code'  => 0,
            'msg'   => 'success',
            'data'  => [
                'row_count' => $count,
                'page_count' => $pagination->pageCount,
                'list' => $list,
                'pay_type_list' => $pay_type_list
            ],
        ];
    }
}
