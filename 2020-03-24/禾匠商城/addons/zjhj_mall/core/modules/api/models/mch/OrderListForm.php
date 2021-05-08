<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 *
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/3/24
 * Time: 14:02
 */


namespace app\modules\api\models\mch;

use app\models\Goods;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderRefund;
use app\models\User;
use app\modules\api\models\ApiModel;
use yii\data\Pagination;

class OrderListForm extends ApiModel
{
    public $mch_id;
    public $status;
    public $keyword;
    public $page;

    public function rules()
    {
        return [
            [['keyword'], 'trim'],
            [['status'], 'default', 'value' => 1,],
            [['page'], 'default', 'value' => 1,],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        //售后订单
        if ($this->status == 5) {
            return $this->searchRefund();
        }

        $query = Order::find()->alias('o')
            ->where(['o.mch_id' => $this->mch_id,]);
        if ($this->keyword) {
            $query->andWhere([
                'OR',
                ['LIKE', 'o.id', $this->keyword,],
                ['LIKE', 'o.order_no', $this->keyword,],
                ['LIKE', 'o.name', $this->keyword,],
                ['LIKE', 'o.mobile', $this->keyword,],
                ['LIKE', 'o.address', $this->keyword,],
                ['LIKE', 'o.remark', $this->keyword,],
            ]);
        }
        //待付款
        if ($this->status == 1) {
            $query->andWhere([
                'o.is_pay' => 0,
                'o.is_delete' => 0,
            ]);
        }
        //已付款待发货
        if ($this->status == 2) {
            $query->andWhere([
                'o.is_pay' => 1,
                'o.is_send' => 0,
                'o.is_delete' => 0,
            ]);
        }
        //已发货待收货
        if ($this->status == 3) {
            $query->andWhere([
                'o.is_send' => 1,
                'o.is_confirm' => 0,
                'o.is_delete' => 0,
            ]);
        }
        //已收货（已完成）
        if ($this->status == 4) {
            $query->andWhere([
                'o.is_confirm' => 1,
                'o.is_delete' => 0,
            ]);
        }
        //已取消
        if ($this->status == 6) {
            $query->andWhere([
                //'o.apply_delete' => 1,
                'o.is_cancel' => 1,
                'o.is_delete' => 0,
            ]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1]);
        $list = $query->offset($pagination->offset)->limit($pagination->limit)->orderBy('o.addtime DESC')
            ->select('o.id,o.order_no,o.total_price,o.addtime,o.is_pay,o.is_send,o.is_confirm,o.is_delete,o.is_cancel')
            ->asArray()->all();
        foreach ($list as $i => $item) {
            $goods_list = OrderDetail::find()->alias('od')->leftJoin(['g' => Goods::tableName()], 'g.id=od.goods_id')
                ->where(['od.is_delete' => 0, 'od.order_id' => $item['id']])
                ->select('od.attr,od.num,od.total_price,g.name,g.cover_pic')
                ->asArray()->all();
            foreach ($goods_list as &$goods) {
                $goods['attr'] = json_decode($goods['attr'], true);
            }
            $list[$i]['goods_list'] = $goods_list;
            $list[$i]['addtime'] = date('Y-m-d H:i', $item['addtime']);
        }
        return [
            'code' => 0,
            'data' => [
                'list' => $list,
            ],
        ];
    }

    //查找售后订单
    public function searchRefund()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = OrderRefund::find()->alias('or')
            ->leftJoin(['o' => Order::tableName()], 'or.order_id=o.id')
            ->leftJoin([
                'od' => OrderDetail::find()->alias('od')->leftJoin(['g' => Goods::tableName()], 'od.goods_id=g.id')->select('od.id,od.total_price,od.goods_id,od.attr,od.num,g.name,g.cover_pic')
            ], 'or.order_detail_id=od.id')
            ->where([
                'o.mch_id' => $this->mch_id,
            ]);
        if ($this->keyword) {
            $query->andWhere([
                'OR',
                ['LIKE', 'od.name', $this->keyword],
                ['LIKE', 'o.order_no', $this->keyword],
                ['LIKE', 'o.id', $this->keyword,],
                ['LIKE', 'o.name', $this->keyword,],
                ['LIKE', 'o.mobile', $this->keyword,],
                ['LIKE', 'o.address', $this->keyword,],
                ['LIKE', 'o.remark', $this->keyword,],
            ]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1]);
        $list = $query->offset($pagination->offset)->limit($pagination->limit)->orderBy('or.addtime DESC')
            ->select([
                'o.order_no', 'o.addtime order_time', 'o.name username', 'o.mobile', 'o.pay_price',
                'od.name', 'od.cover_pic', 'od.attr', 'od.num', 'od.total_price',
                'or.id', 'or.order_refund_no', 'or.refund_price', 'or.addtime order_refund_time', 'or.desc', 'or.type', 'or.pic_list', 'or.status', 'or.refuse_desc',
            ])
            ->asArray()->all();
        foreach ($list as &$item) {
            $item['refund_order'] = true;
            $item['attr'] = json_decode($item['attr'], true);
            $item['pic_list'] = json_decode($item['pic_list'], true);
            $item['order_time'] = date('Y-m-d H:i', $item['order_time']);
            $item['order_refund_time'] = date('Y-m-d H:i', $item['order_refund_time']);
            $item['refund_type'] = $item['type'] == 1 ? '退货退款' : '换货';
        }
        return [
            'code' => 0,
            'data' => [
                'list' => $list,
            ],
        ];
    }
}
