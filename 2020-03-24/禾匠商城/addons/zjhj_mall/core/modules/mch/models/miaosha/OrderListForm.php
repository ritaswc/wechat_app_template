<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/7/20
 * Time: 14:34
 */

namespace app\modules\mch\models\miaosha;

use app\models\common\admin\order\CommonOrderSearch;
use app\models\Goods;
use app\models\MsGoods;
use app\models\MsOrder;
use app\models\MsOrderRefund;
use app\models\Recharge;
use app\models\ReOrder;
use app\models\Shop;
use app\models\User;
use app\modules\mch\models\ExportList;
use app\modules\mch\models\MchModel;
use yii\data\Pagination;

class OrderListForm extends MchModel
{
    public $store_id;
    public $user_id;
    public $keyword;
    public $status;
    public $page;
    public $limit;

    public $flag;//是否导出
    public $is_offline;
    public $clerk_id;
    public $parent_id;
    public $shop_id;

    public $date_start;
    public $date_end;
    public $express_type;
    public $keyword_1;
    public $fields;
    public $platform;//所属平台

    public function rules()
    {
        return [
            [['keyword',], 'trim'],
            [['status', 'page', 'limit', 'user_id', 'is_offline', 'clerk_id', 'shop_id', 'keyword_1', 'platform'], 'integer'],
            [['status',], 'default', 'value' => -1],
            [['page',], 'default', 'value' => 1],
            [['flag', 'date_start', 'date_end', 'express_type'], 'trim'],
            [['flag'], 'default', 'value' => 'no'],
            [['fields'], 'safe']
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = MsOrder::find()->alias('o')->where([
            'o.store_id' => $this->store_id,
        ])
            ->leftJoin(['u' => User::tableName()], 'u.id=o.user_id')
            ->leftJoin(['g' => MsGoods::tableName()], 'g.id=o.goods_id');

        switch ($this->status) {
            case 0:
                $query->andWhere([
                    'o.is_pay' => 0,
                ]);
                break;
            case 1:
                $query->andWhere([
                    'o.is_send' => 0,
                ])->andWhere(['or', ['o.is_pay' => 1], ['o.pay_type' => 2]]);
                break;
            case 2:
                $query->andWhere([
                    'o.is_send' => 1,
                    'o.is_confirm' => 0,
                ])->andWhere(['or', ['o.is_pay' => 1], ['o.pay_type' => 2]]);
                break;
            case 3:
                $query->andWhere([
                    'o.is_send' => 1,
                    'o.is_confirm' => 1,
                ])->andWhere(['or', ['o.is_pay' => 1], ['o.pay_type' => 2]]);
                break;
            case 4:
                break;
            case 5:
                break;
            case 6:
                $query->andWhere(['o.apply_delete' => 1]);
                break;
            default:
                break;
        }
        if ($this->status == 8) {
            $query->andWhere(['o.is_recycle' => 1]);
        } else {
            $query->andWhere(['o.is_recycle' => 0]);
        }

        if ($this->status == 5) {//已取消订单
            $query->andWhere(['or', ['o.is_cancel' => 1], ['o.apply_delete' => 1, 'o.is_delete' => 1]]);
        } else {
            $query->andWhere(['o.is_cancel' => 0, 'o.is_delete' => 0]);
        }

        //TODO 只优化了关键字搜索 持续优化中...
        $commonOrderSearch = new CommonOrderSearch();
        $query = $commonOrderSearch->search($query, $this);
        $query = $commonOrderSearch->keyword($query, $this->keyword_1, $this->keyword);


        //充值异常版本2.2.2.1
        $user_list = ReOrder::find()->alias('ro')->where(['ro.store_id' => $this->store_id, 'ro.is_pay' => 1])
            ->leftJoin(['r' => Recharge::tableName()], 'r.pay_price = ro.pay_price')
            ->andWhere(['>', 'ro.send_price', 0])->andWhere('r.send_price != ro.send_price')->groupBy('ro.user_id')
            ->select(['ro.user_id'])->column();
        if ($this->status == 7) {//异常订单
            $query->andWhere(['o.user_id' => $user_list, 'o.pay_type' => 3]);
        }


        $query1 = clone $query;
        if ($this->flag == "EXPORT") {
            $list_ex = $query1;
            $export = new ExportList();
            $export->is_offline = $this->is_offline;
            $export->order_type = 1;
            $export->fields = $this->fields;
            $export->dataTransform_new($list_ex);
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
            $order_refund = MsOrderRefund::findOne(['store_id' => $this->store_id, 'order_id' => $item['id'], 'is_delete' => 0]);
            $list[$i]['refund'] = "";
            if ($order_refund) {
                $list[$i]['refund'] = $order_refund->status;
            }
            $list[$i]['integral'] = json_decode($item['integral'], true);

            if (isset($item['address_data'])) {
                $list[$i]['address_data'] = \Yii::$app->serializer->decode($item['address_data']);
            }

            $list[$i]['flag'] = 0;
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
        $order_detail_list = MsOrder::find()->alias('od')
            ->leftJoin(['g' => MsGoods::tableName()], 'od.goods_id=g.id')
            ->where([
                //'od.is_delete' => 0,
                'od.id' => $order_id,
            ])->select('od.*,g.name,g.unit,g.cover_pic goods_pic')->asArray()->all();
        foreach ($order_detail_list as $i => $order_detail) {
            $goods = new Goods();
            $goods->id = $order_detail['goods_id'];
            $order_detail_list[$i]['attr_list'] = json_decode($order_detail['attr'], true);
            $order_detail_list[$i]['total_price'] = $order_detail['pay_price'] - $order_detail['express_price'];
        }
        return $order_detail_list;
    }

    public static function getCountData($store_id)
    {
        $form = new OrderListForm();
        $form->limit = 0;
        $form->store_id = $store_id;
        $data = [];

        $form->status = -1;
        $res = $form->search();
        $data['all'] = $res['row_count'];

        $form->status = 0;
        $res = $form->search();
        $data['status_0'] = $res['row_count'];

        $form->status = 1;
        $res = $form->search();
        $data['status_1'] = $res['row_count'];

        $form->status = 2;
        $res = $form->search();
        $data['status_2'] = $res['row_count'];

        $form->status = 3;
        $res = $form->search();
        $data['status_3'] = $res['row_count'];

        $form->status = 5;
        $res = $form->search();
        $data['status_5'] = $res['row_count'];

        return $data;
    }
}
