<?php
/**
 * Created by PhpStorm.
 * User: peize
 * Date: 2017/12/7
 * Time: 20:05
 */

namespace app\modules\mch\models\book;

use app\models\common\admin\order\CommonOrderSearch;
use app\models\Shop;
use app\models\User;
use app\models\YyGoods;
use app\models\YyOrder;
use app\models\YyOrderForm;
use app\modules\mch\models\MchModel;
use app\modules\mch\models\ExportList;
use yii\data\Pagination;

class OrderForm extends MchModel
{
    public $store_id;
    public $user_id;
    public $keyword;

    public $status;

    public $flag;//是否导出

    public $keyword_1;
    public $date_start;
    public $date_end;

    public $fields;
    public $platform;//所属平台

    public function rules()
    {
        return [
            [['keyword', 'date_start', 'date_end'], 'trim'],
            [['status', 'keyword_1', 'platform', 'user_id'], 'integer'],
            [['status',], 'default', 'value' => -1],
            [['flag'], 'trim'],
            [['flag'], 'default', 'value' => 'no'],
            [['fields'], 'safe']
        ];
    }

    /**
     * 预约订单列表
     */
    public function getList()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $clerkQuery = User::find()->where(['store_id' => $this->store_id])->andWhere('id = o.clerk_id')->select('nickname');
        $shopQuery = Shop::find()->where(['store_id' => $this->store_id])->andWhere('id = o.shop_id')->select('name');
        $query = YyOrder::find()->alias('o')
            ->leftJoin(['g' => YyGoods::tableName()], 'g.id=o.goods_id')
            ->leftJoin(['u' => User::tableName()], 'u.id=o.user_id')
            ->andWhere(['o.is_delete' => 0, 'o.store_id' => $this->store_id])
            ->select([
                'o.*',
                'g.name AS goods_name', 'g.cover_pic',
                'u.nickname', 'u.platform',
                'clerk_name' => $clerkQuery,
                'shop_name' => $shopQuery
            ]);


        switch ($this->status) {
            case 0: //未付款
                $query->andWhere([
                    'o.is_pay' => 0,
                    'o.is_cancel' => 0,
                ]);
                break;
            case 1: //待使用
                $query->andWhere([
                    'o.is_pay' => 1,
                    'o.is_use' => 0,
                    'o.is_cancel' => 0,
                    'o.apply_delete' => 0,
                    'o.is_refund' => 0,
                ]);
                break;
            case 2: //待评价
                $query->andWhere([
                    'o.is_pay' => 1,
                    'o.is_use' => 1,
                ]);
                break;
            case 3: //退款
                $query->andWhere([
                    'o.is_pay' => 1,
                    'o.apply_delete' => 1,
                ]);
                break;
            case 4:
                break;
            case 5: //已取消
                $query->andWhere([
                    'o.is_pay' => 0,
                    'o.is_cancel' => 1,
                ]);
                break;
            default:
                break;
        }

        if($this->status == 8){
            $query->andWhere(['is_recycle'=>1]);
        }else{
            $query->andWhere(['is_recycle'=>0]);
        }

        //TODO 只优化了关键字搜索 持续优化中...
        $commonOrderSearch = new CommonOrderSearch();
        $query = $commonOrderSearch->search($query, $this);
        $query = $commonOrderSearch->keyword($query, $this->keyword_1, $this->keyword);

        $query1 = clone $query;
        if ($this->flag == "EXPORT") {
            $list_ex = $query1;
            $export = new ExportList();
            $export->order_type = 3;
            $export->fields = $this->fields;
            $export->is_offline = 1;
            $export->dataTransform_new($list_ex);
        }
        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => 20]);

        $list = $query
            ->orderBy('o.addtime DESC')
            ->offset($p->offset)
            ->limit($p->limit)
            ->asArray()
            ->all();
        foreach ($list as $k => $v) {
            $orderForm = YyOrderForm::find()
                ->select([
                    'key', 'value', 'type'
                ])
                ->andWhere(['store_id' => $this->store_id, 'order_id' => $v['id'], 'goods_id' => $v['goods_id'], 'is_delete' => 0])
                ->all();
            $list[$k]['orderFrom'] = $orderForm;
        }
        return [
            'list' => $list,
            'p' => $p,
            'row_count' => $count,
        ];
    }


    /**
     * @param $order_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getOrderGoodsList($goods_id, $order_id)
    {
        $order_list['form'] = YyOrderForm::find()->select(['key', 'value' ,'type'])->andWhere(['order_id' => $order_id, 'goods_id' => $goods_id])->asArray()->all();

        $order_list['name'] = YyGoods::find()->select('name')->andWhere(['id' => $goods_id])->scalar();

        return $order_list;
    }
}
