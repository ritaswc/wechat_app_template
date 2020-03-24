<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/11
 * Time: 11:45
 */

namespace app\modules\api\models;

use app\hejiang\ApiResponse;
use app\models\Cash;
use yii\data\Pagination;

class CashListForm extends ApiModel
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
            [['limit',], 'default', 'value' => 10],
        ];
    }
    public function getList()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = Cash::find()->where([
            'is_delete'=>0,
            'store_id'=>$this->store_id,
            'user_id'=>$this->user_id
        ]);
        if ($this->status == 0 && $this->status != null) { //待审核
            $query->andWhere(['status'=>0]);
        }
        if ($this->status == 1) {//待打款
            $query->andWhere(['status'=>1]);
        }
        if ($this->status == 2) {//已打款
            $query->andWhere(['in','status',[2,5]]);
        }
        if ($this->status == 3) {//无效
            $query->andWhere(['status'=>3]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1, 'pageSize' => $this->limit]);
        $list = $query->limit($pagination->limit)->offset($pagination->offset)->orderBy('status ASC,addtime DESC')->all();
        $new_list = [];
        /* @var Cash[] $list */
        foreach ($list as $index => $value) {
            $new_list[] = (object)[
                'price'=>$value->price,
                'addtime'=>date('Y-m-d H:i:s', $value->addtime),
                'status'=>Cash::$status[$value->status]
            ];
        }
        $data = [
            'row_count' => $count,//总数
            'page_count' => $pagination->pageCount,//总页数
            'list' => $new_list,
        ];
        return new ApiResponse(0, 'success', $data);
    }
}
