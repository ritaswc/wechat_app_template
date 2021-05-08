<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 *
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/4/26
 * Time: 14:44
 */


namespace app\modules\api\models\mch;

use app\models\Goods;
use app\models\Mch;
use app\models\MchCommonCat;
use app\models\Model;
use app\models\Order;
use app\models\OrderDetail;
use app\modules\api\models\mch\ShopDataForm;
use app\modules\api\models\ApiModel;
use yii\data\Pagination;

class ShopListForm extends ApiModel
{
    public $store_id;
    public $keyword;
    public $cat_id;
    public $page;

    public function rules()
    {
        return [
            [['keyword',], 'trim'],
            [['cat_id', 'page'], 'integer'],
            [['page'], 'default', 'value' => 1,],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = Mch::find()->alias('m')
            ->leftJoin(['mc' => MchCommonCat::tableName()], 'm.mch_common_cat_id = mc.id')
            ->where([
                'm.store_id' => $this->store_id,
                'm.is_delete' => Model::IS_DELETE_FALSE,
                'm.is_open' => Mch::IS_OPEN_TRUE,
                'm.is_lock' => 0,
                'm.is_recommend' => Mch::IS_RECOMMEND_TRUE
            ])->orderBy('m.sort,m.addtime DESC');

        if ($this->cat_id) {
            $query->andWhere(['m.mch_common_cat_id' => $this->cat_id]);
        }

        if ($this->keyword) {
            $query->andWhere([
                'OR',
                ['LIKE', 'm.name', $this->keyword,],
                ['LIKE', 'mc.name', $this->keyword,],
            ]);
        }

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1, 'pageSize' => 10,]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)
            ->select('m.id,m.name,m.logo')
            ->asArray()
            ->all();

        foreach ($list as $k => $item) {
            $goodsList = Goods::find()->where([
                'mch_id' => $item['id'],
                'store_id' => $this->getCurrentStoreId(),
                'is_delete' => Model::IS_DELETE_FALSE,
                'status' => 1,
            ])
                ->select('id,name,price,cover_pic')->asArray()->all();

            $goodsCount = 0;
            foreach ($goodsList as $v) {
                $goodsCount += 1;
            }

            $sellGoodsCount = Order::find()->alias('o')
                ->leftJoin(['od' => OrderDetail::tableName()], 'od.order_id=o.id')
                ->where([
                    'o.mch_id' => $item['id'],
                    'o.store_id' => $this->getCurrentStoreId(),
                    'o.is_pay'  => Order::IS_PAY_TRUE,
                ])
                ->count('od.id');

            $list[$k]['goods_count'] = $goodsCount;
            $list[$k]['goods_list'] = $goodsList;
            $list[$k]['sell_goods_count'] = $sellGoodsCount;

        }

        $cat_list = MchCommonCat::find()
            ->where(['store_id' => $this->store_id, 'is_delete' => 0,])->orderBy('sort ASC')
            ->select('id,name')->all();

        return [
            'code' => 0,
            'data' => [
                'pagination' => $pagination,
                'list' => $list,
                'cat_list' => $cat_list,
            ],
        ];
    }
}
