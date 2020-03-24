<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/10/10
 * Time: 16:03
 */

namespace app\modules\mch\models\diy;


use app\models\Cat;
use app\models\Goods;
use app\models\GoodsCat;
use app\models\Topic;
use app\models\TopicType;
use app\modules\mch\models\MchModel;
use yii\data\Pagination;

class CatForm extends MchModel
{
    public $type;
    public $page;
    public $limit;
    public $keyword;

    public function search()
    {
        $res = [];
        switch($this->type) {
            case 'goods':
                $res = $this->goodsCat();
                break;
            case 'topic':
                $res = $this->goodsTopicCat();
                break;
            default:
                $res = [];
                break;
        }
        return [
            'code' => 0,
            'msg' => 'success',
            'data' => $res
        ];
    }

    private function goodsCat()
    {
        $GoodsCountQuery = Goods::find()->select('count(1)')->alias('g')
            ->where(['g.is_delete' => 0, 'g.type' => 0, 'g.status' => 1, 'g.store_id' => $this->store->id])
            ->leftJoin(['gc' => GoodsCat::tableName()], 'gc.goods_id=g.id')
            ->andWhere([
                'or',
                ['gc.is_delete' => 0, 'gc.store_id' => $this->store->id],
                'isnull(gc.id)'
            ])->andWhere([
                'or',
                'g.cat_id = c.id',
                'gc.cat_id = c.id and g.cat_id = 0'
            ]);
        $query = Cat::find()->alias('c')
            ->select(['c.*','goods_count' => $GoodsCountQuery])
            ->where(['c.store_id' => $this->store->id,'c.is_delete' => 0]);
        if($this->keyword) {
            $query->andWhere(['like', 'c.name', $this->keyword]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        $catList = $query->limit($pagination->limit)->offset($pagination->offset)->asArray()->all();
        return [
            'cat_list' => $catList,
            'count' => $count,
            'page' => $this->page,
            'page_count' => $pagination->pageCount,
            'page_url' => \Yii::$app->urlManager->createUrl(['mch/diy/diy/get-cat',
                'type' => $this->type, 'keyword' => $this->keyword])
        ];
    }

    private function goodsTopicCat()
    {
        $topicCountQuery = Topic::find()->select('count(t.id)')->alias('t')
            ->where(['t.store_id' => $this->store->id, 't.is_delete' => 0])
            ->andWhere('t.type=tt.id');

        $query = TopicType::find()->alias('tt')->select(['tt.*','goods_count' => $topicCountQuery])
            ->where(['store_id' => $this->store->id, 'is_delete' => 0]);

        if($this->keyword) {
            $query->andWhere(['like', 'name', $this->keyword]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit, 'page' => $this->page - 1]);
        $catList = $query->orderBy(['sort' => SORT_ASC])->limit($pagination->limit)
            ->offset($pagination->offset)->asArray()->all();
        return [
            'cat_list' => $catList,
            'count' => $count,
            'page' => $this->page,
            'page_count' => $pagination->pageCount,
            'page_url' => \Yii::$app->urlManager->createUrl(['mch/diy/diy/get-cat',
                'type' => $this->type, 'keyword' => $this->keyword])
        ];
    }
}