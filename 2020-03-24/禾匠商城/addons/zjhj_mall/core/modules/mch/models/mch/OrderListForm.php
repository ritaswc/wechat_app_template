<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/9
 * Time: 11:18
 */

namespace app\modules\mch\models\mch;

use app\models\common\admin\order\CommonOrderSearch;
use app\models\Goods;
use app\models\Mch;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderRefund;
use app\models\User;
use app\modules\mch\models\ExportList;
use app\modules\mch\models\MchModel;
use yii\data\Pagination;

class OrderListForm extends MchModel
{
    public $store_id;
    public $keyword;
    public $status;
    public $page;
    public $limit;
    public $flag;//是否导出
    public $date_start;
    public $date_end;
    public $keyword_1;
    public $seller_comments;
    public $fields;
    public $platform;//所属平台


    public function rules()
    {
        return [
            [['keyword',], 'trim'],
            [['status', 'page', 'limit', 'keyword_1', 'platform'], 'integer'],
            [['status',], 'default', 'value' => -1],
            [['page',], 'default', 'value' => 1],
            [['flag', 'date_start', 'date_end'], 'trim'],
            [['flag'], 'default', 'value' => 'no'],
            [['seller_comments'], 'string'],
            [['fields'], 'safe'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = Order::find()->alias('o')->where([
            'o.store_id' => $this->store_id
        ])->andWhere(['>', 'o.mch_id', 0])
            ->leftJoin(['u' => User::tableName()], 'u.id=o.user_id')
            ->leftJoin(['m' => Mch::tableName()], 'm.id=o.mch_id')
            ->leftJoin(['od' => OrderDetail::tableName()], 'od.order_id=o.id')
            ->leftJoin(['g' => Goods::tableName()], 'g.id=od.goods_id');

        switch ($this->status) {
            case 0:
                $query->andWhere(['o.is_pay' => 0]);
                break;
            case 1:
                $query->andWhere([
                    'o.is_send' => 0
                ])->andWhere(['or', ['o.is_pay' => 1], ['o.pay_type' => 2]]);
                break;
            case 2:
                $query->andWhere([
                    'o.is_send' => 1,
                    'o.is_confirm' => 0
                ]);
                break;
            case 3:
                $query->andWhere([
                    'o.is_send' => 1,
                    'o.is_confirm' => 1
                ]);
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

        if ($this->status == 5) {//已取消订单
            $query->andWhere([
                'or',
                ['o.is_cancel' => 1],
                ['o.is_delete' => 1]
            ]);
        } else {
            $query->andWhere([
                'o.is_cancel' => 0,
                'o.is_delete' => 0,
            ]);
        }

        if ($this->status == 8) {
            $query->andWhere(['o.is_recycle' => 1]);
        } else {
            $query->andWhere(['o.is_recycle' => 0]);
        }

        //TODO 只优化了关键字搜索 持续优化中...
        $commonOrderSearch = new CommonOrderSearch();
        $query = $commonOrderSearch->search($query, $this);
        $query = $commonOrderSearch->keyword($query, $this->keyword_1, $this->keyword);

        $query1 = clone $query;
        if ($this->flag == "EXPORT") {
            $list_ex = $query1->select('o.*')->orderBy('o.addtime DESC')->all();
            $export = new ExportList();
            $export->is_offline = 0;
            $export->order_type = 0;
            $export->fields = $this->fields;
            $export->dataTransform($list_ex);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('o.addtime DESC')
            ->select('o.*,u.nickname,u.platform,m.realname,m.tel,m.name mch_name')->asArray()->all();


        foreach ($list as $i => $item) {
            $list[$i]['goods_list'] = $this->getOrderGoodsList($item['id']);
            $order_refund = OrderRefund::findOne(['store_id' => $this->store_id, 'order_id' => $item['id'], 'is_delete' => 0]);
            $list[$i]['refund'] = "";
            if ($order_refund) {
                $list[$i]['refund'] = $order_refund->status;
            }
            $list[$i]['integral'] = json_decode($item['integral'], true);
            $list[$i]['flag'] = 0;

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
