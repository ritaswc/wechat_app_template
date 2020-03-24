<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/9
 * Time: 17:08
 */

namespace app\modules\mch\models\mch;

use app\models\Goods;
use app\models\Mch;
use app\models\MchCat;
use app\models\MchGoodsCat;
use app\models\GoodsCat;
use app\models\Cat;
use app\modules\mch\models\MchModel;
use yii\data\Pagination;
use app\modules\mch\models\GoodsSearchForm;

class GoodsListForm extends MchModel
{
    public $store_id;
    public $keyword;
    public $shop_name;
    public $status;

    public $limit;
    public $page;

    public $cat;

    public $cat_id;
    public $id;

    public function rules()
    {
        return [
            [['keyword', 'status', 'limit', 'page', 'shop_name'], 'trim'],
            [['keyword','cat'], 'string'],
            [['status'], 'in', 'range' => [1, 2]],
            [['cat_id', 'id'], 'integer'],
            [['limit'],'default','value'=>20]        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }

        $query_cat = MchGoodsCat::find()->alias('gc')
            ->leftJoin(['c' => MchCat::tableName()], 'c.id=gc.cat_id')
            ->select(['gc.goods_id', 'c.name', 'gc.cat_id']);

        $query = Goods::find()->alias('g')->where(['g.store_id' => $this->store_id, 'g.is_delete' => 0])
            ->andWhere(['>', 'g.mch_id', 0])
            ->leftJoin(['gc' => $query_cat], 'gc.goods_id = g.id');

        $cat_query = clone $query;

        if ($this->status) {
            $query->andWhere(['g.status' => $this->status]);
        }
        if ($this->keyword) {
            $query->andWhere(['LIKE', 'g.name', $this->keyword]);
        }
        if ($this->shop_name) {
            $mch = Mch::find()->where(['name' => $this->shop_name])->select('id')->one();
            $query->andWhere(['g.mch_id' => $mch->id]);
        }
        if ($this->cat) {
            $query->andWhere(['gc.name'=>$this->cat]);
        }

        //有商品的分类列表
        $cat_list = $cat_query->groupBy('gc.name')->orderBy(['g.cat_id' => SORT_ASC])
            ->select(['gc.name'])->asArray()->column();
        $query->groupBy('g.id');
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => $this->limit]);
        $query->select('g.*');

        $list = $query->orderBy(['g.sort' => SORT_ASC, 'g.addtime' => SORT_DESC])
            ->limit($pagination->limit)->offset($pagination->offset)->all();

        $cats = Cat::find()->where(['store_id' => $this->store_id, 'is_delete' => 0])->all();
        $cats = $this->simplifyData($cats);

        if(!$cats) {
            $cats = [];
        }
        foreach($list as &$v){
            $goodsCat = GoodsCat::findOne([
                'store_id' => $this->store_id,
                'is_delete' => 0,
                'goods_id' => $v['id'] 
            ]);
            $v['cat_id'] = $goodsCat['cat_id']?$goodsCat['cat_id']:0;

            $name = '无';
            if($v['cat_id']){
                foreach($cats as $v1){
                    if($v1['id']==$v['cat_id'] && $v['cat_id']){
                        $name = $v1['name'];
                    }
                }
            }
            //TODO
            $v['addtime'] = $name;
        }
        unset($v);

        return [
            'list' => $list,
            'cats' => $cats,
            'pagination' => $pagination,
            'cat_list' => $cat_list
        ];
    }

    private function simplifyData($data)
    {
        foreach ($data as $key => $val) {
            $newData[$key] = $val->attributes;
        }
        return $newData;
    }

    public function setCat() {

        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $form = GoodsCat::findOne([
            'store_id' => $this->store_id,
            'is_delete' => 0,
            'goods_id' => $this->id
        ]);

        if($form){
            $form->cat_id = $this->cat_id;

        } else {
            $form = new GoodsCat();
            $form->store_id = $this->store_id;
            $form->goods_id = $this->id;
            $form->addtime = time();
            $form->cat_id = $this->cat_id;
            $form->is_delete = 0;
        };

        if($form->save()){
            return [
                'code' => 0,
                'msg' => '保存成功'
            ];
        } else {
            return [
                'code' => 1,
                'msg' => '保存失败'
            ];
        }
    }
}
