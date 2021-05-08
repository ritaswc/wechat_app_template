<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\modules\mch\models\mch;

use app\models\common\admin\order\CommonOrderStatistics;
use app\models\Goods;
use app\models\Model;
use app\models\Order;
use app\models\OrderDetail;
use app\modules\mch\extensions\Export;
use app\modules\mch\models\ExportList;
use app\modules\mch\models\MchModel;
use app\models\Mch;
use yii\data\Pagination;

class ReportFormsForm extends MchModel
{
    public $keyword;
    public $limit;
    public $status;
    public $date_start;
    public $date_end;
    public $fields;
    public $flag;
    public $mch_id;
    public $page;

    public function rules()
    {
        return [
            [['status', 'limit', 'page', 'mch_id'], 'integer'],
            [['page'], 'default', 'value' => 1],
            [['limit'], 'default', 'value' => 20],
            [['status'], 'default', 'value' => 1],
            [['keyword'], 'trim'],
            [['fields'], 'safe'],
            [['keyword', 'flag', 'date_start', 'date_end'], 'string'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $query = Goods::find()->alias('g')->where([
            'g.is_delete' => Model::IS_DELETE_FALSE, 'g.store_id' => $this->getCurrentStoreId()])
            ->andWhere(['>', 'g.mch_id', 0])
            ->leftJoin(['od' => OrderDetail::tableName()], 'od.goods_id = g.id')
            ->leftJoin(['o' => Order::tableName()], 'o.id = od.order_id')
            ->leftJoin(['m' => Mch::tableName()], 'm.id = g.mch_id')
            ->andWhere([
                'or',
                ['od.is_delete' => 0, 'o.is_delete' => 0, 'o.is_pay' => 1],
                'isnull(od.id)'
            ])->groupBy('g.id');

        if ($this->keyword) {
            $query->andWhere(['like', 'g.name', $this->keyword]);
        }

        if ($this->mch_id) {
            $query->andWhere(['m.id' => $this->mch_id]);
        }


        if ($this->date_start) {
            $query->andWhere(['>', 'o.addtime', strtotime($this->date_start)]);
        }

        if ($this->date_end) {
            $query->andWhere(['<', 'o.addtime', strtotime($this->date_end + 1)]);
        }

        if ($this->flag === Export::EXPORT) {
            $list = $query->select([
                'g.name AS good_name', 'g.id', 'm.name', 'm.logo', 'sum(case when isnull(o.id) then 0 else od.num end) as sales_volume',
                'sum(case when isnull(o.id) then 0 else od.total_price end) as sales_price'
            ])->asArray()->all();

            $export = new ExportList();
            $export->fields = $this->fields;
            $export->MchReportFormsExportData($list);
        }

        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit]);

        if ($this->status == 1) {
            $query->orderBy(['sales_volume' => SORT_DESC]);
        } elseif ($this->status == 2) {
            $query->orderBy(['sales_price' => SORT_DESC]);
        }

        $list = $query->select([
            'g.name AS good_name', 'm.name', 'm.logo', 'sum(case when isnull(o.id) then 0 else od.num end) as sales_volume',
            'sum(case when isnull(o.id) then 0 else od.total_price end) as sales_price'
        ])->offset($p->offset)->limit($p->limit)->asArray()->all();

        $mchList = Mch::find()->where(['store_id' => $this->getCurrentStoreId(), 'is_delete' => Model::IS_DELETE_FALSE])->select('name, id' )->all();

        return [
            'list' => $list,
            'row_count' => $count,
            'pagination' => $p,
            'mch_list' => $mchList
        ];
    }

    public function excelFields()
    {
        $list = [
            [
                'key' => 'id',
                'value' => '商品ID',
                'selected' => 0,
            ],
            [
                'key' => 'name',
                'value' => '店铺名称',
                'selected' => 0,
            ],
            [
                'key' => 'good_name',
                'value' => '商品名称',
                'selected' => 0,
            ],
            [
                'key' => 'sales_volume',
                'value' => '商品销量',
                'selected' => 0,
            ],
            [
                'key' => 'sales_price',
                'value' => '商品销售额',
                'selected' => 0,
            ],
        ];

        return $list;
    }
}