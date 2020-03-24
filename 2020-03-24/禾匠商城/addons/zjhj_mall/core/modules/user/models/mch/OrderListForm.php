<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/3
 * Time: 13:59
 */

namespace app\modules\user\models\mch;

use app\models\Goods;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderRefund;
use app\models\Recharge;
use app\models\ReOrder;
use app\models\Shop;
use app\models\User;
use app\modules\mch\extensions\Export;
use app\modules\user\models\UserModel;
use yii\data\Pagination;

class OrderListForm extends UserModel
{
    public $store_id;
    public $mch_id;
    public $flag;//是否导出
    public $limit;
    public $page;


    public $status;
    public $is_offline;

    public $date_start;
    public $date_end;
    public $express_type;
    public $keyword;
    public $keyword_1;
    public $seller_comments;
    public $is_recycle;

    public function rules()
    {
        return [
            [['keyword',], 'trim'],
            [['status','is_recycle', 'page', 'limit', 'is_offline', 'keyword_1'], 'integer'],
            [['status',], 'default', 'value' => -1],
            [['page',], 'default', 'value' => 1],
            [['limit',], 'default', 'value' => 20],
            [['flag', 'date_start', 'date_end', 'express_type'], 'trim'],
            [['flag'], 'default', 'value' => 'no'],
            [['seller_comments'], 'string'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = Order::find()->alias('o')->where(['o.store_id'=>$this->store_id,'o.mch_id'=>$this->mch_id])
            ->leftJoin(['u'=>User::tableName()], 'u.id=o.user_id');

        if ($this->status == 0) {//未付款
            $query->andWhere([
                'o.is_pay' => 0,
            ]);
        }
        if ($this->status == 1) {//待发货
            $query->andWhere([
                'o.is_pay' => 1,
                'o.is_send' => 0,
            ]);
        }
        if ($this->status == 2) {//待确认收货
            $query->andWhere([
                'o.is_pay' => 1,
                'o.is_send' => 1,
                'o.is_confirm' => 0,
            ]);
        }
        if ($this->status == 3) {//已确认收货
            $query->andWhere([
                'o.is_pay' => 1,
                'o.is_send' => 1,
                'o.is_confirm' => 1,
            ]);
        }
        if ($this->status == 4) {//售后
        }
        if ($this->status == 5) {//已取消订单
            $query->andWhere(['or', ['o.is_cancel' => 1], ['o.is_delete' => 1]]);
        } else {
            if ($this->is_recycle!=1) {
                $query->andWhere(['o.is_cancel' => 0, 'o.is_delete' => 0]);
            }
        }
        if ($this->status == 6) {//申请取消待处理订单
            $query->andWhere(['o.apply_delete'=>1]);
        }
        if ($this->date_start) {
            $query->andWhere(['>=', 'o.addtime', strtotime($this->date_start)]);
        }
        if ($this->date_end) {
            $query->andWhere(['<=', 'o.addtime', strtotime($this->date_end) + 86400]);
        }

        if ($this->keyword) {//关键字查找
            if ($this->keyword_1 == 1) {
                $query->andWhere(['like', 'o.order_no', $this->keyword]);
            }
            if ($this->keyword_1 == 2) {
                $query->andWhere(['like', 'u.nickname', $this->keyword]);
            }
            if ($this->keyword_1 == 3) {
                $query->andWhere(['like', 'o.name', $this->keyword]);
            }
        }
        if ($this->is_offline) {
            $query->andWhere(['o.is_offline' => $this->is_offline]);
        }
        //充值异常版本2.2.2.1
        $user_list = ReOrder::find()->alias('ro')->where(['ro.store_id'=>$this->store_id,'ro.is_pay'=>1])
            ->leftJoin(['r'=>Recharge::tableName()], 'r.pay_price = ro.pay_price')
            ->andWhere(['>','ro.send_price',0])->andWhere('r.send_price != ro.send_price')->groupBy('ro.user_id')
            ->select(['ro.user_id'])->column();
        if ($this->status == 7) {//异常订单
            $query->andWhere(['o.user_id'=>$user_list,'o.pay_type'=>3]);
        }
        if ($this->is_recycle==1) {
            $query->andWhere(['o.is_recycle'=>1]);
        } else {
            $query->andWhere(['o.is_recycle'=>0]);
        }

        $query1 = clone $query;
        if ($this->flag == "EXPORT") {
            $list_ex = $query1->select('o.*,u.nickname')->orderBy('o.addtime DESC')->asArray()->all();
            foreach ($list_ex as $i => $item) {
                $list_ex[$i]['goods_list'] = $this->getOrderGoodsList($item['id']);
            }
            Export::order_2($list_ex, $this->is_offline);
        }

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('o.addtime DESC')
            ->select('o.*,u.nickname,u.platform')->asArray()->all();

        foreach ($list as $i => $item) {
            $list[$i]['goods_list'] = $this->getOrderGoodsList($item['id']);
            if ($item['is_offline'] == 1 && $item['is_send'] == 1) {
                $user = User::findOne(['id' => $item['clerk_id'], 'store_id' => $this->store_id]);
                $list[$i]['clerk_name'] = $user->nickname;
            }
            if ($item['shop_id'] && $item['shop_id'] != 0) {
                $shop = Shop::find()->where(['store_id' => $this->store_id, 'id' => $item['shop_id']])->asArray()->one();
                $list[$i]['shop'] = $shop;
            }
            $order_refund = OrderRefund::findOne(['store_id' => $this->store_id, 'order_id' => $item['id'], 'is_delete' => 0]);
            $list[$i]['refund'] = "";
            if ($order_refund) {
                $list[$i]['refund'] = $order_refund->status;
            }
            $list[$i]['integral'] = json_decode($item['integral'], true);
            $list[$i]['flag'] = 0;
            if ($item['pay_type'] == 3 && in_array($item['user_id'], $user_list)) {
                $list[$i]['flag'] = 0;
            }

            if (isset($item['address_data'])) {
                $list[$i]['address_data'] = \Yii::$app->serializer->decode($item['address_data']);
            }
        }
        return [
            'row_count' => $count,
            'page_count' => $pagination->pageCount,
            'pagination' => $pagination,
            'list' => $list,
        ];
    }

    public function getOrderGoodsList($order_id)
    {
        $order_detail_list = OrderDetail::find()->alias('od')
            ->leftJoin(['g' => Goods::tableName()], 'od.goods_id=g.id')
            ->where([
                'od.is_delete' => 0,
                'od.order_id' => $order_id,
            ])->select('od.*,g.name,g.unit')->asArray()->all();
        foreach ($order_detail_list as $i => $order_detail) {
            $goods = new Goods();
            $goods->id = $order_detail['goods_id'];
            $order_detail_list[$i]['goods_pic'] = $goods->getGoodsPic(0)->pic_url;
            $order_detail_list[$i]['attr_list'] = json_decode($order_detail['attr']);
        }
        return $order_detail_list;
    }
}
