<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 *
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/3/22
 * Time: 14:18
 */


namespace app\modules\api\models\mch;

use app\models\Goods;
use app\models\Order;
use app\models\OrderDetail;
use app\modules\api\models\ApiModel;
use yii\data\Pagination;

class GoodsListForm extends ApiModel
{
    public $mch_id;
    public $status;
    public $keyword;
    public $page;

    public function rules()
    {
        return [
            ['keyword', 'trim'],
            ['mch_id', 'required'],
            ['status', 'default', 'value' => 1,],
            ['page', 'default', 'value' => 1,],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = Goods::find()->alias('g')->where(['g.mch_id' => $this->mch_id, 'g.is_delete' => 0,]);
        if ($this->status == 1) {
            $query->andWhere(['g.status' => 1,])->orderBy('g.mch_sort,g.addtime DESC');
        }
        if ($this->status == 2) {
            $query->andWhere(['g.goods_num' => 0,])->orderBy('g.mch_sort,g.addtime DESC');
        }
        if ($this->status == 3) {
            $query->andWhere(['g.status' => 0,])->orderBy('g.mch_sort,g.addtime DESC');
        }
        if ($this->status == 4) {
            $query->orderBy('g.addtime DESC');
        }
        if ($this->keyword) {
            $query->andWhere(['LIKE', 'g.name', $this->keyword,]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1]);
        $list = $query
            ->leftJoin([
                'od' => OrderDetail::find()->alias('od')
                    ->leftJoin(['o' => Order::tableName()], 'od.order_id=o.id')
                    ->select('SUM(od.num) AS sale_num,od.goods_id')->where(['od.is_delete' => 0, 'o.is_pay' => 1,])->groupBy('od.goods_id')
            ], 'od.goods_id=g.id')
            ->limit($pagination->limit)->offset($pagination->offset)
            ->select(['g.id', 'g.name', 'g.cover_pic', 'g.price', 'g.status', 'g.attr', 'IF(od.sale_num,od.sale_num,0) sale_num'])->asArray()->all();
        foreach ($list as $i => $item) {
            $m = new Goods();
            $m->id = $item['id'];
            $m->attr = $item['attr'];
            $list[$i]['goods_num'] = $m->getNum();
            unset($list[$i]['attr']);
        }
        return [
            'code' => 0,
            'data' => [
                'list' => $list,
            ],
        ];
    }
}
