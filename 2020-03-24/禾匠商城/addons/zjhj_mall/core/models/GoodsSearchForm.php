<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/10/11
 * Time: 17:11
 */

namespace app\models;

use app\models\common\CommonGoodsAttr;
use yii\data\Pagination;

class GoodsSearchForm extends Model
{
    public $store_id;
    public $keyword;
    public $page;
    public $limit;
    public $is_delete;
    public $status;

    public function rules()
    {
        return [
            [['store_id',], 'integer',],
            [['keyword',], 'trim',],
            [['page',], 'default', 'value' => 1,],
            [['limit',], 'default', 'value' => 20,],
            [['is_delete',], 'default', 'value' => 0,],
            [['status',], 'default', 'value' => 1,],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = MsGoods::find()->where([
            'store_id' => $this->store_id,
            'is_delete' => $this->is_delete,
            'status' => $this->status,
        ]);
        if ($this->keyword) {
            $query->andWhere([
                'LIKE', 'name', $this->keyword,
            ]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1, 'pageSize' => $this->limit]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('id DESC')->all();

        $newList = [];
        foreach ($list as $i => $item) {
            $newItem = [];
            foreach ($item as $k => $v) {
                $newItem[$k] = $v;
            }
            $newItem['attr_group_list'] = $item->getAttrData();
            $newItem['attr'] = CommonGoodsAttr::getCheckedAttr($item);
            $newItem['price'] = $item['original_price'];
            $newItem['is_level'] = $item->is_discount;
//            $newItem['attr']['sell_num'] = $item->getSalesVolume();
            $newList[] = $newItem;
        }

        return [
            'code' => 0,
            'data' => [
                'row_count' => $count,
                'page_count' => $pagination->pageCount,
                'list' => $newList,
            ],
        ];
    }
}
