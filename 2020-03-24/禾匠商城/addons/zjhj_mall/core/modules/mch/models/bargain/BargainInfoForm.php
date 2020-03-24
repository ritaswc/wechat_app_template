<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/8/16
 * Time: 9:27
 */

namespace app\modules\mch\models\bargain;


use app\models\BargainOrder;
use app\models\Goods;
use app\modules\mch\models\MchModel;
use yii\data\Pagination;

class BargainInfoForm extends MchModel
{
    public $store;
    public $status;
    public $keyword;

    public $limit;
    public $page;

    public function rules()
    {
        return [
            [['status', 'limit', 'page'], 'integer'],
            [['status'], 'default', 'value' => -1],
            [['limit'], 'default', 'value' => 10],
            [['keyword'],'trim'],
            [['keyword'],'string'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $res = $this->getBargainOrder();
        return $res;
    }

    // 查找所有的砍价信息
    private function getBargainOrder()
    {
        $query = BargainOrder::find()->alias('bo')->where([
            'bo.store_id' => $this->store->id, 'bo.is_delete' => 0
        ])->joinWith(['goods g'])->with('user', 'orderUser.user');

        if (($this->status && $this->status != -1) || $this->status == 0) {
            $query->andWhere(['bo.status' => $this->status]);
        }
        if($this->keyword || $this->keyword == 0){
            $query->andWhere(['like', 'g.name', $this->keyword]);
        }

        $count = $query->count();
        $p = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit]);

        $list = $query->limit($p->limit)->offset($p->offset)->orderBy(['bo.status' => SORT_ASC, 'bo.addtime' => SORT_DESC])->all();

        return [
            'list'=>$list,
            'pagination'=>$p
        ];
    }
}